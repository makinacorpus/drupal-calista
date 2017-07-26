<?php

namespace MakinaCorpus\Drupal\Calista\Datasource;

use MakinaCorpus\Calista\Datasource\AbstractDatasource;
use MakinaCorpus\Calista\Datasource\DefaultDatasourceResult;
use MakinaCorpus\Calista\Datasource\Filter;
use MakinaCorpus\Calista\Datasource\Query;
use MakinaCorpus\Calista\Error\ConfigurationError;
use MakinaCorpus\Drupal\Calista\Datasource\QueryExtender\DrupalPager;
use MakinaCorpus\Drupal\Calista\Entity\DatabaseRow;

/**
 * Entity datasource that is able to stream data, but will sadly not invoke
 * any load hook.
 *
 * @todo handle fields
 */
class EntityDatasource extends AbstractDatasource
{
    private $baseTable;
    private $database;
    private $entityType;
    private $fields = [];
    private $fieldsEnabled = true;
    private $fulltextField;
    private $idColumn;
    private $joins = [];
    private $optionnalJoins = [];
    private $revisionColumn;

    /**
     * Default constructor
     */
    public function __construct(\DatabaseConnection $database, $entityType, $fieldsEnabled = true)
    {
        $this->database = $database;
        $this->entityType = $entityType;
        $this->fieldsEnabled = $fieldsEnabled;

        $this->buildColumnDescription();
        if ($this->fieldsEnabled) {
            $this->buildFieldColumnDescription();
        }
    }

    /**
     * Aggregate all possible information on the entity
     */
    private function buildColumnDescription()
    {
        if (!$info = entity_get_info($this->entityType)) {
            throw new ConfigurationError(sprintf("entity type '%s' does not exist", $this->entityType));
        }

        $this->baseTable = $info['base table'];

        $excluded = [];

        // Identifier and revision identifier
        foreach (['id' => "Identifier", 'revision' => "Revision identifier", 'label' => "Label", 'language' => "Language"] as $alias => $label) {
            if (!empty($info['entity keys'][$alias])) {
                $column = $info['entity keys'][$alias];
                $excluded[] = $column;

                $this->fields[$alias] = [
                    'label'   => $label,
                    'column'  => 'base.'.$column,
                    'choices' => null,
                ];
            }
        }

        // Enable fulltext search if there is a label field
        if (isset($this->fields['label'])) {
            $this->fulltextField = $this->fields['label']['column'];
        }
        if (isset($this->fields['id'])) {
            $this->idColumn = $this->fields['id']['column'];
        }
        if (isset($this->fields['revision'])) {
            $this->revisionColumn = $this->fields['revision']['column'];
        }

        // Bundles
        if (!empty($info['bundles']) && isset($info['entity keys']['bundle'])) {
            $column = $info['entity keys']['bundle'];
            $excluded[] = $column;

            $choices = [];
            foreach ($info['bundles'] as $bundle => $description) {
                $choices[$bundle] = $description['label'];
            }

            $this->fields['bundle'] = [
                'label'   => "Bundle",
                'column'  => 'base.'.$column,
                'choices' => $choices,
            ];
        }

        // Base and revision table columns
        foreach (['base table' => 'base', 'revision table' => 'revision'] as $table => $alias) {
            if (isset($info['schema_fields_sql'][$table])) {
                $schema = drupal_get_schema($info[$table]);

                foreach ($info['schema_fields_sql'][$table] as $column) {
                    $columnAlias = 'base' === $alias ? $column : $alias.'_'.$column;

                    if (isset($this->fields[$columnAlias]) || in_array($column, $excluded)) {
                        continue;
                    }

                    if (isset($schema['fields'][$column]['description'])) {
                        $label = $schema['fields'][$column]['description'];
                    } else {
                        $label = $column;
                    }

                    $this->fields[$columnAlias] = [
                        'label'   => $label,
                        'column'  => $alias.'.'.$column,
                        'choices' => null,
                    ];
                }
            }
        }

        if (!empty($info['revision table'])) {
            $idColumn = $info['entity keys']['id'];
            $revColumn = $info['entity keys']['revision'];

            $this->joins['revision'] = [
                'type'      => 'INNER',
                'table'     => $info['revision table'],
                'condition' => sprintf(
                    "base.%s = revision.%s AND base.%s = revision.%s",
                    $idColumn, $idColumn, $revColumn, $revColumn
                )
            ];
        }
    }

    /**
     * Aggregate all possible information on the entity
     */
    private function buildFieldColumnDescription()
    {
        if (!$info = entity_get_info($this->entityType)) {
            throw new ConfigurationError(sprintf("entity type '%s' does not exist", $this->entityType));
        }

        $idColumn = $info['entity keys']['id'];
        $done = [];

        foreach (array_keys($info['bundles']) as $bundle) {
            if (!$instances = field_info_instances($this->entityType, $bundle)) {
                continue;
            }

            foreach ($instances as $fieldName => $instance) {

                // Avoid doing the same field twice
                if (isset($done[$fieldName])) {
                    continue;
                }
                $done[$fieldName] = true;

                // Ensure field exists
                if (!$field = field_info_field($fieldName)) {
                    continue;
                }
                // We cannot proceed with multiple values, for now, without
                // using group by on those
                // @todo handle this gracefully
                if (1 !== (int)$field['cardinality']) {
                    continue;
                }
                // Do not use deleted fields, or non SQL handled fields
                if ($field['deleted'] || 'field_sql_storage' !== $field['storage']['type']) {
                    continue;
                }

                $table = _field_sql_storage_tablename($field);
                // @todo handle conflicts
                if ('field_' === substr($fieldName, 0, 6)) {
                    $tableAlias = substr($fieldName, 6);
                } else {
                    $tableAlias = $fieldName;
                }

                $this->joins[$tableAlias] = [
                    'type'      => 'LEFT',
                    'table'     => $table,
                    'condition' => sprintf(
                        "%s.entity_type = '%s' AND %s.entity_id = base.%s",
                        $tableAlias, $this->entityType, $tableAlias, $idColumn
                    )
                ];

                foreach ($field['columns'] as $name => $description) {
                    $column = _field_sql_storage_columnname($fieldName, $name);
                    $columnAlias = $tableAlias.'_'.$name;

                    if (!empty($instance['label'])) {
                        $label = $instance['label'];
                    } else if (!empty($description['description'])) {
                        $label = $description['description'];
                    } else {
                        $label = $fieldName;
                    }

                    $this->fields[$columnAlias] = [
                        'label'   => $label,
                        'column'  => $tableAlias.'.'.$column,
                        'choices' => null,
                    ];
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemClass()
    {
        return DatabaseRow::class;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsFulltextSearch()
    {
        return null !== $this->fulltextField;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsPagination()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsStreaming()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $ret = [];

        foreach ($this->fields as $alias => $description) {
            $filter = new Filter($alias, $description['label']);
            if ($description['choices']) {
                $filter->setChoicesMap($description['choices']);
            }

            $ret[] = $filter;
        }

        return $ret;
    }

    /**
     * Implementors should override this method to add their sorts
     *
     * {@inheritdoc}
     */
    public function getSorts()
    {
        $ret = [];

        foreach ($this->fields as $alias => $description) {
            $ret[$alias] = $description['label'];
        }

        return $ret;
    }

    /**
     * Process field filters
     *
     * @param \SelectQueryInterface $select
     * @param Query $query
     */
    protected function applyFilters(\SelectQueryInterface $select, Query $query)
    {
        foreach ($this->fields as $name => $description) {
            if ($query->has($name)) {
                $select->condition($description['column'], $query->get($name));
            }
        }
    }

    /**
     * Process paging and full text search
     *
     * @return \SelectQuery
     *   It can be an extended query, so use this object.
     */
    final protected function process(\SelectQueryInterface $select, Query $query)
    {
        $sortField = null;

        if ($query->hasSortField()) {
            $sortField = $query->getSortField();

            $select->orderBy($this->fields[$sortField]['column'], Query::SORT_DESC === $query->getSortOrder() ? 'desc' : 'asc');
        }

        // Add a predictible ordering column
        if ($sortField !== $this->idColumn) {
            $select->orderBy($this->idColumn, Query::SORT_DESC === $query->getSortOrder() ? 'desc' : 'asc');
        }

        if ($this->fulltextField) {
            if ($searchstring = $query->getSearchString()) {
                $select->condition($this->fulltextField, '%'.db_like($searchstring).'%', 'LIKE');
            }
        }

        $this->applyFilters($select, $query);

        return $select;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(Query $query)
    {
        $pagerEnabled = $query->getInputDefinition()->isPagerEnabled();

        $select = $this->database->select($this->baseTable, 'base');
        $select = $this->process($select, $query);

        foreach ($this->joins as $alias => $description) {
            $select->addJoin($description['type'], $description['table'], $alias, $description['condition']);
        }

        // Field selection, this should be driven by allowed view properties
        // @todo find a way to propagate properties from view to here
        foreach ($this->fields as $alias => $description) {
            $select->addExpression(sprintf("'%s'", $this->entityType), 'entity_type');
            $select->addExpression($description['column'], $alias);
        }

        if ($pagerEnabled) {
            /** @var \MakinaCorpus\Drupal\Calista\Datasource\QueryExtender\DrupalPager $select */
            $select = $select->extend(DrupalPager::class);
            $select->setDatasourceQuery($query);
        }

        $result = $select->execute();
        /** @var \PDOStatement $result */
        $result->setFetchMode(\PDO::FETCH_ASSOC);

        // Preload and set nodes at once
        $result = new DefaultDatasourceResult($this->getItemClass(), function () use ($result) {
            foreach ($result as $row) {
                yield new DatabaseRow((array)$row);
            }
        });

        if ($pagerEnabled) {
            $result->setTotalItemCount($select->getTotalCount());
        }

        return $result;
    }
}
