// Generates the Meridian Chrono Group logo files.
const fs = require('fs');

function ticks(ink) {
  let t = '';
  for (let i = 1; i < 60; i++) {
    const major = i % 5 === 0;
    const a = (i * 6 * Math.PI) / 180;
    const r1 = 49, r2 = major ? 44.5 : 47;
    const p = (r) => [(60 + r * Math.sin(a)).toFixed(2), (60 - r * Math.cos(a)).toFixed(2)];
    const [x1, y1] = p(r1), [x2, y2] = p(r2);
    t += `<line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" stroke-width="${major ? 1.1 : 0.5}"/>`;
  }
  return `<g stroke="${ink}" opacity=".85">${t}</g>`;
}

function emblem(ink, gold) {
  return `<circle cx="60" cy="60" r="56.5" fill="none" stroke="${gold}" stroke-width="1"/>
<circle cx="60" cy="60" r="51" fill="none" stroke="${ink}" stroke-width=".6" opacity=".55"/>
${ticks(ink)}
<line x1="60" y1="3.5" x2="60" y2="116.5" stroke="${gold}" stroke-width=".9"/>
<path d="M56.9 6.6h6.2L60 12z" fill="${gold}"/>
<g fill="${ink}">
<rect x="42" y="42" width="1.8" height="36"/>
<polygon points="42,42 48.2,42 62,73.5 59.6,78"/>
<polygon points="58.9,77.2 72.6,42 74.2,42 60.6,78"/>
<rect x="72.6" y="42" width="5.6" height="36"/>
<rect x="38.6" y="41.4" width="9.8" height="1.1"/>
<rect x="72" y="41.4" width="9.4" height="1.1"/>
<rect x="38.6" y="77.5" width="8.6" height="1.1"/>
<rect x="69.4" y="77.5" width="12" height="1.1"/>
</g>`;
}

const FONT_SERIF = `'Bodoni Moda', Didot, 'Bodoni 72', 'Bodoni MT', Georgia, serif`;
const FONT_SANS = `Jost, 'Futura PT', Futura, 'Avenir Next', 'Segoe UI', sans-serif`;

function emblemFile(bg, ink, gold) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="512" height="512">
<title>Meridian Chrono Group emblem</title>
${bg ? `<rect width="120" height="120" fill="${bg}"/>` : ''}
${emblem(ink, gold)}
</svg>`;
}

function lockupFile(bg, ink, gold) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 140" width="1120" height="280">
<title>Meridian Chrono Group</title>
<style>@import url('https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,500&amp;family=Jost:wght@500&amp;display=swap');</style>
${bg ? `<rect width="560" height="140" fill="${bg}"/>` : ''}
<g transform="translate(20 20)">${emblem(ink, gold).replace(/\n/g, '')}</g>
<line x1="164" y1="34" x2="164" y2="106" stroke="${gold}" stroke-width="1"/>
<text x="190" y="82" font-family="${FONT_SERIF}" font-size="44" letter-spacing="10" fill="${ink}">MERIDIAN</text>
<text x="192" y="108" font-family="${FONT_SANS}" font-size="12.5" letter-spacing="7.4" fill="${gold}">CHRONO GROUP</text>
</svg>`;
}

const out = 'meridian-site/logo';
fs.mkdirSync(out, { recursive: true });
fs.writeFileSync(`${out}/meridian-emblem-ink.svg`, emblemFile('#F7F3EA', '#152724', '#A88A55'));
fs.writeFileSync(`${out}/meridian-emblem-ivory.svg`, emblemFile('#152724', '#F7F3EA', '#C9AE78'));
fs.writeFileSync(`${out}/meridian-lockup-ink.svg`, lockupFile('#F7F3EA', '#152724', '#A88A55'));
fs.writeFileSync(`${out}/meridian-lockup-ivory.svg`, lockupFile('#152724', '#F7F3EA', '#C9AE78'));
console.log('ok');
