// resources/js/utils/dateConverter.js

/**
 * 値が数値であるかどうかを判定します。
 * @param {*} value
 * @returns {boolean}
 */
export function isNumeric(value) {
    return !isNaN(parseFloat(value)) && isFinite(value);
}

/**
 * Excelのシリアル値をJavaScriptのDateオブジェクトに変換します。
 * Excelのシリアル値は1900年1月1日を基準とし、1900年2月29日が存在すると誤認識しているため、
 * そのずれを考慮して25569を引きます。（1970/1/1からの日数 - 1日）
 * @param {number} serial Excelのシリアル値
 * @returns {Date}
 */
export function excelSerialDateToJSDate(serial) {
    const daysBeforeEpoch = 25569; // 1970年1月1日までの日数 (Excelの1900年うるう年バグを考慮)
    const millisecondsPerDay = 24 * 60 * 60 * 1000;
    const date = new Date((serial - daysBeforeEpoch) * millisecondsPerDay);
    return date;
}
