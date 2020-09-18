
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

window.flatpickr = require('flatpickr');

window.he = require('he');

const Arabic = require('flatpickr/dist/l10n/ar.js').ar;

flatpickr.localize(Arabic);

window.pdfobject = require('pdfobject');
