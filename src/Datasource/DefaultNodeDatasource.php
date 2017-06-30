<?php

namespace MakinaCorpus\Drupal\Calista\Datasource;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use MakinaCorpus\Calista\Datasource\AbstractDatasource;
use MakinaCorpus\Calista\Datasource\Filter;
use MakinaCorpus\Calista\Datasource\Query;
use MakinaCorpus\Drupal\Calista\Datasource\QueryExtender\DrupalPager;

/**
 * Base implementation for node admin datasource, that should fit most use cases.
 */
class DefaultNodeDatasource extends AbstractDatasource
{
    use StringTranslationTrait;

    private $database;
    private $entityManager;
    private $pager;

    /**
     * Default constructor
     *
     * @param \DatabaseConnection $db
     * @param EntityManager $entityManager
     */
    public function __construct(\DatabaseConnection $database, EntityManager $entityManager)
    {
        $this->database = $database;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemClass()
    {
        return NodeInterface::class;
    }

    /**
     * Get Drupal database connection
     *
     * @return \DatabaseConnection
     */
    final protected function getDatabase()
    {
        return $this->database;
    }

    /**
     * Get Drupal database connection
     *
     * @return EntityManager
     */
    final protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Implementors should override this method to add their filters
     *
     * {@inheritdoc}
     */
    public function getFilters()
    {
        // @todo build commong database filters for node datasource into some
        //   trait or abstract implemetnation to avoid duplicates
        return [
            (new Filter('status', $this->t("Published")))
                ->setChoicesMap([
                    1 => $this->t("Yes"),
                    0 => $this->t("No"),
                ]),
            (new Filter('promote', $this->t("Promoted to front page")))
                ->setChoicesMap([
                    1 => $this->t("Yes"),
                    0 => $this->t("No"),
                ]),
            (new Filter('sticky', $this->t("Sticky")))
                ->setChoicesMap([
                    1 => $this->t("Yes"),
                    0 => $this->t("No"),
                ]),
            (new Filter('type', $this->t("Type")))
                ->setChoicesMap(node_type_get_names()),
            (new Filter('history_user_id', $this->t("User (history)"))),
                /* ->setChoicesMap(node_type_get_names()), @todo use callback */
            (new Filter('revision_user_id', $this->t("User (node revision)"))),
                /* ->setChoicesMap(node_type_get_names()), @todo use callback */
            (new Filter('user_id', $this->t("User (node owner)"))),
                /* ->setChoicesMap(node_type_get_names()), @todo use callback */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSorts()
    {
        return [
            'n.created'     => $this->t("creation date"),
            'n.changed'     => $this->t("lastest update date"),
            'h.timestamp'   => $this->t('most recently viewed'),
            'n.status'      => $this->t("status"),
            'n.uid'         => $this->t("owner"),
            'n.title'       => $this->t("title"),
        ];
    }

    /**
     * Preload pretty much everything to make admin listing faster
     *
     * You should call this.
     *
     * @param int[] $nodeIdList
     *
     * @return NodeInterface[]
     *   The loaded nodes
     */
    protected function preloadDependencies(array $nodeIdList)
    {
        $userIdList = [];
        $nodeList = $this->entityManager->getStorage('node')->loadMultiple($nodeIdList);

        foreach ($nodeList as $node) {
            $userIdList[$node->uid] = $node->uid;
        }

        if ($userIdList) {
            $this->entityManager->getStorage('user')->loadMultiple($userIdList);
        }

        return $nodeList;
    }

    /**
     * Implementors should override this method to apply their filters
     *
     * @param \SelectQuery $select
     * @param Query $query
     */
    protected function applyFilters(\SelectQuery $select, Query $query)
    {
        if ($query->has('status')) {
            $select->condition('n.status', $query->get('status'));
        }
        if ($query->has('promote')) {
            $select->condition('n.promote', $query->get('promote'));
        }
        if ($query->has('sticky')) {
            $select->condition('n.sticky', $query->get('sticky'));
        }
        if ($query->has('type')) {
            $select->condition('n.type', $query->get('type'));
        }
    }

    /**
     * Returns a column on which an arbitrary sort will be added in order to
     * ensure that besides user selected sort order, it will be  predictible
     * and avoid sort glitches.
     *
     * @return string
     */
    protected function getPredictibleOrderColumn()
    {
        return 'n.nid';
    }

    /**
     * Get pager
     *
     * @return DrupalPager
     */
    final protected function getPager()
    {
        if (!$this->pager) {
            throw new \LogicException("you cannot fetch the pager before the database query has been created");
        }

        return $this->pager;
    }

    /**
     * Should the implementation add group by n.nid clause or not
     *
     * It happens that some complex implementation will add their own groups,
     * case in which we should not interfer.
     *
     * @return bool
     */
    protected function addGroupby()
    {
        return false;
    }

    /**
     * Create node select query, override this to change it
     *
     * @param array $query
     *   Incoming query, might be modified for business purposes
     *
     * @return \SelectQuery
     */
    final protected function createSelectQuery(Query $query)
    {
        $select = $this->getDatabase()->select('node', 'n')->fields('n', ['nid'])->addTag('node_access');

        if ($this->addGroupby()) {
            $select->groupBy('n.nid');
        }

        return $select;
    }

    /**
     * Implementors must set the node table with 'n' as alias, and call this
     * method for the datasource to work correctly.
     *
     * @param \SelectQuery $select
     * @param Query $query
     *
     * @return \SelectQuery
     *   It can be an extended query, so use this object.
     */
    final protected function process(\SelectQuery $select, Query $query)
    {
        $sortOrder = Query::SORT_DESC === $query->getSortOrder() ? 'desc' : 'asc';
        if ($query->hasSortField()) {
            $select->orderBy($query->getSortField(), $sortOrder);
        }
        $select->orderBy($this->getPredictibleOrderColumn(), $sortOrder);

        if ($searchString = $query->getSearchString()) {
            $select->condition('n.title', '%' . db_like($searchString) . '%', 'LIKE');
        }

        // Also add a few joins,  that might be useful later
        if ($query->has('history_user_id')) {
            $select->leftJoin('history', 'h', "h.nid = n.nid AND h.uid = :history_uid", [':history_uid' => $query->get('history_user_id')]);
        } else {
            $select->leftJoin('history', 'h', "h.nid = n.nid AND 1 = 0");
        }

        // This is where it potentially gets ugly in term of performance
        if ($query->has('revision_user_id')) {
            $revSelect = $this
                ->database
                ->select('node_revision', 'r')
                ->condition('r.uid', $query->has('revision_user_id'))
                ->where("r.nid = n.nid")
                ->range(0, 1)
            ;
            $revSelect->addExpression('1');

            $select->exists($revSelect);
        }

        $this->applyFilters($select, $query);

        /** @var \MakinaCorpus\Drupal\Calista\Datasource\QueryExtender\DrupalPager $pager */
        $this->pager = $select->extend(DrupalPager::class);
        $this->pager->setDatasourceQuery($query);

        return $this->pager;
    }

    /**
     * {@inheritdoc}
     */
    protected function createResult(array $items, $totalCount = null)
    {
        if (null === $totalCount) {
            $totalCount = $this->getPager()->getTotalCount();
        }

        return parent::createResult($items, $totalCount);
    }

    /**
     * {@inheritdoc}
     *
     * In order to validate, we don't need sort etc...
     */
    public function validateItems(Query $query, array $idList)
    {
        $select = $this->createSelectQuery($query);
        $this->applyFilters($select, $query);

        // Do an except (interjection) to determine if some identifiers from
        // the input set are not in the dataset returned by the query, but SQL
        // even standard does not allow us to do that easily, hence the
        // array_diff() call after fetching the col.
        // @todo this is unperformant, comparing count result would be better
        //   but more dangerous SQL-wise (we must be absolutely sure that nid
        //   colum is deduplicated)
        $col = $select->condition('n.nid', $idList)->execute()->fetchCol();

        return array_diff($idList, $col) ? false : true;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(Query $query)
    {
        $select = $this->createSelectQuery($query);
        $select = $this->process($select, $query);
        $items  = $this->preloadDependencies($select->execute()->fetchCol());

        // Preload and set nodes at once
        return $this->createResult($items);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsFulltextSearch()
    {
        return true;
    }
}
