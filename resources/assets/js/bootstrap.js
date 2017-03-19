
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

import Flatpickr from 'flatpickr';

import {ar as Arabic} from 'flatpickr/dist/l10n/ar.js';

Flatpickr.localize(Arabic);
