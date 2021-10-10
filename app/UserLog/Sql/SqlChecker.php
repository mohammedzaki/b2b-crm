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

namespace App\UserLog\Sql;

use App\UserLog\Models\EntityName;
use App\UserLog\Models\LogAction;
use App\UserLog\Utils\Constants;
use Illuminate\Support\Facades\Log;


/**
 * Description of SqlChecker
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 */
class SqlChecker {

    public function isDML($query)
    {
        if ($this->strContainsAll($query, ['insert ', ' into ', ' values '])) {
            return TRUE;
        } else if ($this->strContainsAll($query, ['update', 'set', '='])) {
            return TRUE;
        } else if ($this->strContainsAll($query, ['delete ', ' from '])) {
            return TRUE;
        } else if ($this->strContainsAll($query, ['create ', ' table '])) {
            return FALSE;
        }
        return FALSE;
    }

    public function getActionLogId($query)
    {
        if ($this->strContainsAll($query, ['insert ', ' into ', ' values '])) {
            return LogAction::insert;
        } else if ($this->strContainsAll($query, ['delete ', ' from ']) || $this->strContainsAll($query, ['update ', ' set ', '`deleted_at`', ' = '])) {
            return LogAction::delete;
        } else if ($this->strContainsAll($query, ['update ', ' set ', ' = '])) {
            return LogAction::update;
        }
        return FALSE;
    }

    public function getEntity($query)
    {
//        $entities = EntityName::all();
//
//        foreach ($entities as $entity) {
//            if (str_contains($query, $entity->name)) {
//                return $entity;
//            }
//        }
        return NULL;
    }

    public function getRowId($query)
    {
        preg_match_all('/(where `id` = )(\d+)/', $query, $matches);
        return $matches[2][0];
    }

    public function checkIgnoredTable($query)
    {
        return !$this->strContainsAny($query, config(Constants::IGNORED_TABLES));
    }

    private function strContainsAll($str, array $needles)
    {
        foreach ($needles as $needle) {
            if (!str_contains($str, $needle)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    private function strContainsAny($str, array $needles)
    {
        foreach ($needles as $needle) {
            if (str_contains($str, $needle)) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
