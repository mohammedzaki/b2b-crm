<?php

/*
 * The MIT License
 *
 * Copyright 2017 Mohammed Zaki mohammedzaki.dev@gmail.com.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\UserLog;

use App\UserLog\Events\UserLogCreatedEvent;
use App\UserLog\Models\EntityName;
use App\UserLog\Models\LogAction;
use App\UserLog\Models\UserLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\UserLog\Format\SqlLineFormat;
use App\UserLog\Sql\PrepareSql;
use App\UserLog\Sql\SqlChecker;
use App\UserLog\Filesystem\LogFileHandler;
use App\UserLog\Utils\Constants;
use App\UserLog\Utils\Helpers;

/**
 * Description of SqlLogger
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 */
class SqlLogger
{

    /**
     * @var LogFileHandler
     */
    private $logFileHandler;

    /**
     *
     * @var PrepareSql
     */
    private $prepareSql;

    /**
     *
     * @var SqlChecker
     */
    private $sqlChecker;

    public function __construct()
    {
        $this->logFileHandler = new LogFileHandler();
        $this->prepareSql     = new PrepareSql();
        $this->sqlChecker     = new SqlChecker;
    }

    public function log($sql, $bindings)
    {
//        if ($this->sqlChecker->isDML($sql) && $this->sqlChecker->checkIgnoredTable($sql)) {
//            $sqlQuery = $this->prepareSql->prepare($sql, $bindings);
//            $entity   = null;//$this->sqlChecker->getEntity($sqlQuery);
//            $actionId = $this->sqlChecker->getActionLogId($sqlQuery);
//            $logData  = '';
//            $rowId    = NULL;
//            if ($actionId == LogAction::update) {
//                $rowId   = $this->sqlChecker->getRowId($sqlQuery);
//                $prefix  = "update `{$entity->name}` set ";
//                $suffix  = " where `id` = {$rowId}";
//                $logData = str_replace($prefix, '', $sqlQuery);
//                $logData = str_replace($suffix, '', $logData);
//                preg_match_all('/(, `updated_at` = \"[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\")/', $logData, $matches);
//                $logData = str_replace($matches[1][0], '', $logData);
//            } elseif ($actionId == LogAction::insert) {
//                $logData = $sqlQuery;
//            }
//            $userLogData = [
//                'entity_id' => 12,
//                'action_id' => $actionId,
//                'row_id'    => $rowId,
//                'user_id'   => \Auth::user()->id,
//                'log_data'  => $logData
//            ];
//            //event(new UserLogCreatedEvent($userLogData));
//        }
    }


}
