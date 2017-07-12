<?php

namespace MakinaCorpus\Drupal\Calista\Page;

use MakinaCorpus\Calista\View\Stream\CsvStreamView;
use MakinaCorpus\Drupal\Calista\Datasource\DefaultAccountDatasource;

/**
 * Export users as a CSV file
 */
class AccountCsvPageDefinition extends AccountPageDefinition
{
    protected $datasourceId = DefaultAccountDatasource::class;
    protected $viewType = CsvStreamView::class;

    /**
     * Renders name
     */
    public function renderName($value, array $options, $item)
    {
        return check_plain($value);
    }

    /**
     * Renders mail
     */
    public function renderMail($value, array $options, $item)
    {
        return check_plain($value);
    }
}
