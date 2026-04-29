#!/usr/bin/env node
/**
 * Generate PNG and ICO assets from the SVG logos in public/images/.
 *
 * Run: npm run logo:build
 */

import { readFile, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import sharp from 'sharp';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const imagesDir = path.join(root, 'public', 'images');

const targets = [
    { svg: 'logo.svg',              out: 'logo-light.png',     width: 1024 },
    { svg: 'logo-dark-bg.svg',      out: 'logo-dark.png',      width: 1024 },
    { svg: 'logo-mark.svg',         out: 'logo-mark.png',      width: 1024 },
    { svg: 'logo-mark-dark-bg.svg', out: 'logo-mark-dark.png', width: 1024 },
    { svg: 'favicon.svg',           out: 'favicon-16.png',     width: 16  },
    { svg: 'favicon.svg',           out: 'favicon-32.png',     width: 32  },
    { svg: 'favicon.svg',           out: 'favicon-180.png',    width: 180 }, // apple-touch-icon
    { svg: 'favicon.svg',           out: 'favicon-192.png',    width: 192 },
    { svg: 'favicon.svg',           out: 'favicon-512.png',    width: 512 },
];

async function render({ svg, out, width }) {
    const svgPath = path.join(imagesDir, svg);
    const outPath = path.join(imagesDir, out);
    const buffer = await readFile(svgPath);

    await sharp(buffer, { density: 384 })
        .resize({ width, fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
        .png({ compressionLevel: 9 })
        .toFile(outPath);

    console.log(`  ${svg.padEnd(28)} -> ${out} (${width}px)`);
}

async function buildIco() {
    // Minimal ICO container that embeds 16, 32 and 48 px PNGs.
    const sizes = [16, 32, 48];
    const pngs = await Promise.all(
        sizes.map(async (size) => {
            const buffer = await readFile(path.join(imagesDir, 'favicon.svg'));
            return {
                size,
                data: await sharp(buffer, { density: 384 })
                    .resize({ width: size, height: size, fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
                    .png({ compressionLevel: 9 })
                    .toBuffer(),
            };
        })
    );

    const header = Buffer.alloc(6);
    header.writeUInt16LE(0, 0);              // reserved
    header.writeUInt16LE(1, 2);              // type = icon
    header.writeUInt16LE(pngs.length, 4);    // image count

    const dirEntries = [];
    const dataChunks = [];
    let offset = 6 + pngs.length * 16;

    for (const { size, data } of pngs) {
        const entry = Buffer.alloc(16);
        entry.writeUInt8(size === 256 ? 0 : size, 0); // width
        entry.writeUInt8(size === 256 ? 0 : size, 1); // height
        entry.writeUInt8(0, 2);                       // palette
        entry.writeUInt8(0, 3);                       // reserved
        entry.writeUInt16LE(1, 4);                    // color planes
        entry.writeUInt16LE(32, 6);                   // bits per pixel
        entry.writeUInt32LE(data.length, 8);          // image size
        entry.writeUInt32LE(offset, 12);              // offset
        dirEntries.push(entry);
        dataChunks.push(data);
        offset += data.length;
    }

    const ico = Buffer.concat([header, ...dirEntries, ...dataChunks]);
    const outPath = path.join(root, 'public', 'favicon.ico');
    await writeFile(outPath, ico);
    console.log(`  favicon.svg                  -> favicon.ico (${sizes.join(',')}px)`);
}

console.log('Generating logo PNGs from SVG sources...\n');
for (const target of targets) {
    await render(target);
}
await buildIco();
console.log('\nDone.');
