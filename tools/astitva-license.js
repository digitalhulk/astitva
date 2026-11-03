#!/usr/bin/env node
// Astitva license tool. Runs on your own computer only; never upload it with the theme.
//
//   node astitva-license.js init                          create the signing key (once)
//   node astitva-license.js issue example.com "Owner"     create a key for one domain
//   node astitva-license.js issue "*" "Owner"             create a key that works on any domain
//   node astitva-license.js check ASTV-...                show what a key contains
//
// The private key lives in C:\Users\dell2\astitva-license\private.pem (or ASTITVA_KEY_DIR).
// Anyone with that file can create keys, so keep it out of GitHub, cloud sync and chats.
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

const DIR = process.env.ASTITVA_KEY_DIR || path.join(require('os').homedir(), 'astitva-license');
const PRIV = path.join(DIR, 'private.pem');
const b64u = buf => Buffer.from(buf).toString('base64').replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
const fromB64u = s => Buffer.from(s.replace(/-/g, '+').replace(/_/g, '/'), 'base64');

function publicKeyB64(priv) {
  const jwk = crypto.createPublicKey(priv).export({ format: 'jwk' });
  return fromB64u(jwk.x).toString('base64');
}

const [cmd, a, b] = process.argv.slice(2);
if (cmd === 'init') {
  if (fs.existsSync(PRIV)) {
    console.log('Key already exists at ' + PRIV + '. Not overwriting it, because old licenses would stop working.');
  } else {
    fs.mkdirSync(DIR, { recursive: true });
    const { privateKey } = crypto.generateKeyPairSync('ed25519');
    fs.writeFileSync(PRIV, privateKey.export({ format: 'pem', type: 'pkcs8' }), { mode: 0o600 });
    console.log('Private key saved: ' + PRIV);
  }
  console.log('PUBLIC_KEY=' + publicKeyB64(crypto.createPrivateKey(fs.readFileSync(PRIV))));
} else if (cmd === 'issue') {
  if (!a) throw new Error('Give a domain, for example: issue example.com "Owner"');
  const priv = crypto.createPrivateKey(fs.readFileSync(PRIV));
  const domain = a.toLowerCase().replace(/^https?:\/\//, '').replace(/\/.*$/, '').replace(/^www\./, '');
  const payload = Buffer.from(JSON.stringify({ d: domain, n: b || '', i: Math.floor(Date.now() / 1000), e: 0 }));
  const sig = crypto.sign(null, payload, priv);
  console.log('ASTV-' + b64u(payload) + '.' + b64u(sig));
} else if (cmd === 'check') {
  const m = /^ASTV-([A-Za-z0-9_-]+)\.([A-Za-z0-9_-]+)$/.exec((a || '').trim());
  if (!m) throw new Error('Not an Astitva key');
  const payload = fromB64u(m[1]);
  const ok = fs.existsSync(PRIV) && crypto.verify(null, payload, crypto.createPublicKey(crypto.createPrivateKey(fs.readFileSync(PRIV))), fromB64u(m[2]));
  console.log(JSON.parse(payload.toString()), ok ? 'signature valid' : 'signature NOT valid');
} else {
  console.log('Usage: init | issue <domain> "<owner>" | check <key>');
}
