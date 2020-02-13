<?php


namespace app\widgets\Graph;


use app\assets\ChartJsAssetBundle;
use DateTime;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use Yii;
use yii\bootstrap\Widget;
use yii\db\Exception;
use function GuzzleHttp\Psr7\str;

/**
 * Class Graph
 * @package app\widgets\Graph
 *
 * @property Dom\HtmlNode $doom
 */
class Graph extends Widget
{
    public $datum_key;

    public $data = [];

    public $fileName;
    public $doom;

    public $balance = 0;
    public $maxElement;
    public $skipTop;
    public $skipDown;

    public function init()
    {
        parent::init();

        ChartJsAssetBundle::register( $this->getView() );

        if (empty($this->data) || !$this->datum_key) {
            throw new \Exception('Data empty');
        }

        foreach ($this->data as $datum) {

            if (!(
                is_string($datum['file'])
                && is_integer($datum['firstDataIndex'])
                && is_integer($datum['secondDataIndex'])
                && is_integer($datum['labelRowIndex'])
                && is_string($datum['graphName'])
            ))
            {
                throw new \Exception('Some required parameters are not passed');
            }
        }
    }

    public function run()
    {
        $summaryData = [];

        foreach ($this->data as $datum) {

            $this->fileName     = $datum['file'];
            $this->balance      = $this->setBalance($datum);
            $this->skipTop      = isset($datum['skipTop']) ? (int)$datum['skipTop'] : 0;
            $this->skipDown     = isset($datum['skipDown']) ? (int)$datum['skipDown'] : 0;
            $this->maxElement   = isset($datum['maxElement']) ? (int)$datum['maxElement'] : false;

            $filePath = $this->getFilePath();
            if ($filePath === false) continue;

            $this->setDoom($filePath);

            $table              = $this->getTable(); if ($table === false) continue;
            $headerTr           = $this->getHeaderTr($table, $datum['labelRowIndex']); if ($headerTr === false) continue;
            $headerTrLabelArr   = $this->getHeaderTrLabelArr($headerTr); if ($headerTrLabelArr === false) continue;
            $dataTrArr          = $this->getDataTrArr($table, $datum); if ($dataTrArr === false) continue;

            list($chartLabels, $chartData, $headerTrLabelArr, $dataTrArr, $compactChartData) = $this->getChartData($dataTrArr, $headerTrLabelArr, $datum);

            $balanceIndex = count($headerTrLabelArr);

            $summaryData['datum'][] = [
                'chartLabels'       => $chartLabels,
                'chartData'         => $chartData,
                'dataTrArr'         => $dataTrArr,
                'firstDataIndex'    => $datum['firstDataIndex'],
                'secondDataIndex'   => $datum['secondDataIndex'],
                'headerTrLabelArr'  => $headerTrLabelArr,
                'balanceIndex'      => $balanceIndex,
                'skipTop'           => $datum['skipTop'],
                'skipDown'          => $datum['skipDown'],
                'graphName'         => $datum['graphName'],
                'compactChartData'  => $compactChartData,
            ];
        }

        if (!count($summaryData)) {
            Yii::$app->session->setFlash('error', 'Data no found or is not valid');
            return false;
        }

        $summaryData['labels'] = [];
        foreach ($summaryData['datum'] as $datum) {
            $summaryData['labels'] = array_merge($summaryData['labels'], $datum['chartLabels']);
        }
        sort($summaryData['labels']);

        $summaryData['datum_key'] = $this->datum_key;

        return $this->render('render', compact('summaryData'));

    }

    private function setBalance($datum)
    {
        $balance = 0;
        if (isset($datum['balance']) && (float)$datum['balance']) {
            if (stripos($datum['balance'] , '-') !== false) {
                $balance -= trim($datum['balance'], '-');
            } else {
                $balance += $datum['balance'];
            }
        }
        return (float)$balance;
    }

    private function getTable(int $index = 0)
    {
        $result = false;
        try {
            $result = $this->doom->find('table')[$index];
            if (!is_object($result) || !$result->count())
                return false;
        } catch (ChildNotFoundException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $result;
    }

    private function getHeaderTr(Dom\HtmlNode $table, int $index)
    {
        $result = false;
        try {
            $result = $table->find('tr')[$index];
            if (!is_object($result) || !$result->count())
                return false;
        } catch (ChildNotFoundException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $result;
    }

    private function getHeaderTrLabelArr(Dom\HtmlNode $headerTr)
    {
        $headerTrLabelArr = [];
        $position = 0;

        try {
            $ar = $headerTr->find('td');
            if (is_object($ar) && !$ar->count()) {
                $ar = $headerTr->find('th');
            }
        } catch (ChildNotFoundException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }

        if (!is_object($ar) || !$ar->count())
            return false;

        foreach ($ar as $td) {
            if ($td->colspan) {
                $position += $td->colspan;
            } else {
                $position++;
            }
            $headerTrLabelArr[$position] = $td->text;
        }

        return $headerTrLabelArr;
    }

    private function getDataTrArr(Dom\HtmlNode $table, array $datum)
    {
        try {
            $dataTr = $table->find('tr');
            if (!is_object($dataTr) || !$dataTr->count())
                return false;
        } catch (ChildNotFoundException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }

        $dataTrCnt = $dataTr->count() - $this->skipTop - $this->skipDown;
        $dataTrArr = [];

        for ($i = 0 + $this->skipTop; $i <= $dataTrCnt; $i++) {
            try {
                $curDataTr = $table->find('tr', $i);
                if (!is_object($curDataTr) || !$curDataTr->count())
                    return false;

            } catch (ChildNotFoundException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                return false;
            }
            $position = 0;

            try {
                $ar = $curDataTr->find('td');
                if (is_object($ar) && !$ar->count()) {
                    $ar = $curDataTr->find('th');
                }
            } catch (ChildNotFoundException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                return false;
            }
            if (!is_object($ar) || !$ar->count()) {
                return false;
            }

            foreach ($ar as $td)
            {
                if ($td->colspan) {
                    $position += $td->colspan;
                } else {
                    $position++;
                }
                $dataTrArr[$i][$position] = $td->text;
            }

            if ($this->maxElement && ($i-1) >= $this->maxElement) {
                break;
            }
        }

        return $dataTrArr;
    }

    private function getChartData(array $dataTrArr, array $headerTrLabelArr, array $datum)
    {
        $chartLabels = [];
        $chartData = [];
        $compactChartData = [];

        foreach ($dataTrArr as $position => $item)
        {
            if (!isset($item[$datum['secondDataIndex']]) || !isset($item[$datum['secondDataIndex']]))
                continue;

            $item[$datum['secondDataIndex']] = str_replace(' ', '', $item[$datum['secondDataIndex']]);

            $sum = floatval($item[$datum['secondDataIndex']]);

            if (stripos($sum , '-') !== false) {
                $this->balance -= trim($sum, '-');
            } else {
                $this->balance += $sum;
            }

            $dataTrArr[$position][count($headerTrLabelArr)+1] = $this->balance;

            array_push($compactChartData, ['x' => $item[$datum['firstDataIndex']], 'y' => $this->balance]);

            array_push($chartLabels, $item[$datum['firstDataIndex']]);
            array_push($chartData, $this->balance);

        }

        array_push($headerTrLabelArr, 'Balance');

        return [$chartLabels, $chartData, $headerTrLabelArr, $dataTrArr, $compactChartData];
    }

    private function getFilePath()
    {
        $filePath = $this->fileName;
        if (!file_exists($filePath)) return false;

        $file_info = pathinfo($filePath);
        if ($file_info['extension'] != 'html') return false;

        return $filePath;
    }

    private function setDoom($filePath)
    {
        $this->doom = new Dom();
        $this->doom->loadFromFile($filePath);
    }
}
