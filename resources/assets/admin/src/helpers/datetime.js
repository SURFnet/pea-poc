/* eslint-disable no-shadow */
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import * as daysjsLocale from 'dayjs/locale/en';

export const formats = {
    date: 'DD-MM-YYYY',
    month: 'MM-YYYY',
    time: 'HH:mm',
};

dayjs.locale(daysjsLocale);
dayjs.extend(relativeTime);

/**
 * Get the formatted date.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function date(date) {
    if (date === null || date === undefined) {
        return '';
    }

    return dayjs(date).format('DD-MM-YYYY');
}

/**
 * Get the formatted long date (with the full month name).
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function longDate(date) {
    if (date === null || date === undefined) {
        return '';
    }

    return dayjs(date).format('D MMMM YYYY');
}

/**
 * Format a date with day name, number and month name.
 * This is useful for upcoming dates that are not far in the future or past.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function upcomingDate(date) {
    if (date === null || date === undefined) {
        return '';
    }

    return dayjs(date).format('dddd D MMMM');
}

/**
 * Get the formatted date and time
 *
 * @param {string} datetime
 *
 * @returns {string}
 */
export function dateTime(datetime) {
    if (date === null || date === undefined) {
        return '';
    }

    return dayjs(datetime).format('DD-MM-YYYY HH:mm');
}

/**
 * Get the formatted time.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function time(date) {
    return dayjs(date).format('HH:mm');
}

/**
 * Get the formatted day.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function day(date) {
    return dayjs(date).format('DD');
}

/**
 * Get the formatted month in its short human readable form.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function shortMonth(date) {
    return dayjs(date).format('MMM');
}

/**
 * Get the formatted month in its human readable form.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function longMonth(date) {
    return dayjs(date).format('MMMM');
}

/**
 * Get the formatted month with the year.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function month(date) {
    return dayjs(date).format('MM-YYYY');
}

/**
 * Get the formatted year.
 *
 * @param {string} date
 *
 * @returns {string}
 */
export function year(date) {
    return dayjs(date).format('YYYY');
}

/**
 * Get the date and time in a long format.
 *
 * @param {string} datetime
 *
 * @returns {string}
 */
export function longDatetime(datetime) {
    return dayjs(datetime).format('D MMMM YYYY, HH:mm');
}

/**
 * Get the unix timestamp (in seconds).
 *
 * @param  {string} date
 *
 * @returns {number}
 */
export function unix(date) {
    return dayjs(date).unix();
}

/**
 * Get the date from now in a human readable format.
 *
 * @param {string} datetime
 *
 * @returns {string}
 */
export function fromNow(datetime) {
    return dayjs(datetime).fromNow();
}

/**
 * Get the date in readable format.
 *
 * @param {string} datetime
 *
 * @returns {string}
 */
export function readableDate(datetime) {
    return dayjs(datetime).format('D MMMM YYYY');
}
