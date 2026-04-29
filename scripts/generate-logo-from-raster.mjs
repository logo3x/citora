#!/usr/bin/env node
/**
 * Build logo variants from the raster source image.
 *
 * Run: npm run logo:build:raster
 */

import { readFile, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import sharp from 'sharp';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const imagesDir = path.join(root, 'public', 'images');
const source = path.join(imagesDir, 'logo-source.png');

// Bounding boxes computed from the alpha channel of logo-source.png.
const FULL_CROP = { left: 170, top: 180, width: 685, height: 550 };
const ICON_CROP = { left: 340, top: 180, width: 330, height: 310 };

async function makeFullLogo() {
    await sharp(source)
        .extract(FULL_CROP)
        .resize({ width: 1024, fit: 'inside' })
        .png({ compressionLevel: 9, palette: true, quality: 90 })
        .toFile(path.join(imagesDir, 'logo-light.png'));
    console.log('  logo-light.png (1024px full logo)');
}

async function makeMark() {
    const buffer = await sharp(source)
        .extract(ICON_CROP)
        .png({ compressionLevel: 9, palette: true, quality: 90 })
        .toBuffer();

    await sharp(buffer)
        .resize({ width: 1024, fit: 'contain', background: { r: 255, g: 255, b: 255, alpha: 0 } })
        .png({ compressionLevel: 9, palette: true, quality: 90 })
        .toFile(path.join(imagesDir, 'logo-mark.png'));
    console.log('  logo-mark.png (1024px icon crop)');

    // Dark-bg variant: replace the navy strokes/fills with white so the
    // calendar outline survives on dark backgrounds. Orange and teal are
    // left untouched.
    const { data, info } = await sharp(buffer).raw().toBuffer({ resolveWithObject: true });
    const out = Buffer.from(data);
    for (let i = 0; i < out.length; i += info.channels) {
        const r = out[i], g = out[i + 1], b = out[i + 2];
        if (r < 80 && g < 80 && b < 130 && b > r) {
            out[i] = 255;
            out[i + 1] = 255;
            out[i + 2] = 255;
        }
    }
    await sharp(out, { raw: { width: info.width, height: info.height, channels: info.channels } })
        .resize({ width: 1024, fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
        .png({ compressionLevel: 9, palette: true, quality: 90 })
        .toFile(path.join(imagesDir, 'logo-mark-dark.png'));
    console.log('  logo-mark-dark.png (1024px icon, navy -> white)');
}

async function makeFavicons() {
    const sizes = [16, 32, 180, 192, 512];
    const iconBuffer = await sharp(source).extract(ICON_CROP).png().toBuffer();

    for (const size of sizes) {
        await sharp(iconBuffer)
            .resize({ width: size, height: size, fit: 'contain', background: { r: 255, g: 255, b: 255, alpha: 0 } })
            .png({ compressionLevel: 9, palette: true, quality: 90 })
            .toFile(path.join(imagesDir, `favicon-${size}.png`));
    }
    console.log(`  favicon-{${sizes.join(',')}}.png`);

    // Multi-size .ico
    const icoSizes = [16, 32, 48];
    const pngs = await Promise.all(
        icoSizes.map(async (size) => ({
            size,
            data: await sharp(iconBuffer)
                .resize({ width: size, height: size, fit: 'contain', background: { r: 255, g: 255, b: 255, alpha: 0 } })
                .png({ compressionLevel: 9, palette: true, quality: 90 })
                .toBuffer(),
        }))
    );

    const header = Buffer.alloc(6);
    header.writeUInt16LE(0, 0);
    header.writeUInt16LE(1, 2);
    header.writeUInt16LE(pngs.length, 4);

    const dirEntries = [];
    const dataChunks = [];
    let offset = 6 + pngs.length * 16;
    for (const { size, data } of pngs) {
        const entry = Buffer.alloc(16);
        entry.writeUInt8(size, 0);
        entry.writeUInt8(size, 1);
        entry.writeUInt8(0, 2);
        entry.writeUInt8(0, 3);
        entry.writeUInt16LE(1, 4);
        entry.writeUInt16LE(32, 6);
        entry.writeUInt32LE(data.length, 8);
        entry.writeUInt32LE(offset, 12);
        dirEntries.push(entry);
        dataChunks.push(data);
        offset += data.length;
    }
    await writeFile(path.join(root, 'public', 'favicon.ico'), Buffer.concat([header, ...dirEntries, ...dataChunks]));
    console.log('  favicon.ico (16/32/48)');
}

console.log('Building logo variants from raster source (logo-source.png)...\n');
await makeFullLogo();
await makeMark();
await makeFavicons();
console.log('\nDone.');
