<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Reports\V2;

/**
 *
 * @author mohammedzaki
 */
interface IReport {

    function setReportRefs();

    function getReportData($withUserLog = false);

    function getReportName();
}
