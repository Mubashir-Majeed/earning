# Hostinger Deployment Fix - npm Command Not Found

## Problem:
Hostinger shared hosting par `npm` command available nahi hai, isliye assets build nahi ho rahe.

## Solution: Locally Build Karke Upload Karo

### Step 1: Local Machine Par Build Karo

Apni local machine par (jahan project hai) ye commands chalao:

```bash
# 1. Dependencies install karo (agar nahi kiye)
npm install

# 2. Production build karo
npm run build
```

Ye command `public/build` folder create karega jisme sab CSS/JS files hongi.

### Step 2: Build Folder Check Karo

Build ke baad verify karo:
```bash
# Check karo ke build folder bana hai
ls -la public/build
```

Aapko `manifest.json` aur CSS/JS files dikhni chahiye.

### Step 3: Hostinger Par Upload Karo

**Option A: FTP/SFTP se:**
1. `public/build` folder ko Hostinger ke `public_html/earning/public/build` par upload karo
2. Puri `public/build` folder upload karo (manifest.json aur sab files ke saath)

**Option B: cPanel File Manager se:**
1. cPanel → File Manager
2. `public_html/earning/public/` folder mein jao
3. `build` folder upload karo (agar nahi hai to create karo)

### Step 4: Hostinger Par Laravel Commands Chalao

SSH se ye commands chalao:

```bash
cd ~/domains/raahehaq.com/public_html/earning

# Clear caches
php artisan optimize:clear

# Cache karo
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Storage link (agar images/files nahi aa rahe)
php artisan storage:link

# Permissions set karo
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Step 5: Verify Karo

1. Browser mein site kholo
2. Hard refresh karo (Ctrl+Shift+R)
3. Developer Tools (F12) → Network tab check karo
4. CSS/JS files load ho rahi hain ya nahi verify karo

## Alternative: Hostinger Node.js Check

Agar Hostinger par Node.js available hai (rare cases mein), to ye try karo:

```bash
# Node.js version check karo
node --version

# NPM check karo
npm --version

# Agar available hai to:
npm install
npm run build
```

## Important Files to Upload:

1. ✅ `public/build/` - **MOST IMPORTANT** (ye locally build karke upload karo)
2. ✅ `.env` file (Hostinger par sahi values ke saath)
3. ✅ `storage/` folder permissions (755)
4. ✅ `bootstrap/cache/` folder permissions (755)

## Quick Checklist:

- [ ] Local par `npm run build` chala diya
- [ ] `public/build` folder Hostinger par upload kar diya
- [ ] `.env` file sahi configure hai
- [ ] `php artisan optimize:clear` chala diya
- [ ] `php artisan config:cache` chala diya
- [ ] Permissions set kar diye (755)
- [ ] Browser cache clear kiya (hard refresh)

## Troubleshooting:

**Agar abhi bhi design kharab hai:**

1. **Check .env file:**
   ```env
   APP_URL=https://raahehaq.com/earning
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Check public/build folder exists:**
   ```bash
   ls -la public/build
   # Should show manifest.json and CSS/JS files
   ```

3. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify Vite manifest:**
   ```bash
   cat public/build/.vite/manifest.json
   ```

## Note:
Har baar code change ke baad, locally `npm run build` chalao aur `public/build` folder ko Hostinger par upload karo.

