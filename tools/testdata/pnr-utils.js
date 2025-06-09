// pnr-utils.js - v1

/**
 * Konverterar personnummer till standardformat: YYMMDD-NNNX
 * @param {string} pnr - 12-siffrigt personnummer (ex: 197904042384)
 * @returns {string} - Standardiserat format eller originalet
 */
function formatPersonnummer(pnr) {
  if (!/^\d{12}$/.test(pnr)) return pnr;
  const yymmdd = pnr.slice(2, 8);
  const nnnx = pnr.slice(8);
  return `${yymmdd}-${nnnx}`;
}

/**
 * Validerar Luhn + kön
 * @param {string} pnr - 12-siffrigt personnummer
 * @param {string} kön - 'man' eller 'kvinna'
 * @returns {boolean}
 */
function valideraPersonnummer(pnr, kön) {
  if (!/^\d{12}$/.test(pnr)) return false;

  const digits = pnr.slice(2, 11).split('').map(Number); // 9 siffror (utan kontrollsiffra)
  let sum = 0;

  for (let i = 0; i < digits.length; i++) {
    let val = digits[i] * (i % 2 === 0 ? 2 : 1);
    if (val > 9) val -= 9;
    sum += val;
  }

  const kontroll = (10 - (sum % 10)) % 10;
  const sista = Number(pnr[11]);

  const luhnOk = kontroll === sista;

  const nästSista = Number(pnr[10]);
  const könOk = kön === 'kvinna' ? nästSista % 2 === 0 : nästSista % 2 === 1;

  return luhnOk && könOk;
}
