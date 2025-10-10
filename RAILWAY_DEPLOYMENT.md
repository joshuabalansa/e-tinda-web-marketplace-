# Railway Deployment Guide for E-Tinda Marketplace

## Prerequisites
- Railway account (sign up at https://railway.app)
- GitHub repository with your Laravel app
- Railway CLI (optional but recommended)

## Step 1: Prepare Your Repository

1. **Commit all changes** to your repository:
   ```bash
   git add .
   git commit -m "Prepare for Railway deployment"
   git push origin main
   ```

## Step 2: Create Railway Project

1. **Go to Railway Dashboard**: https://railway.app/dashboard
2. **Click "New Project"**
3. **Select "Deploy from GitHub repo"**
4. **Choose your repository** (e-tinda-marketplace)
5. **Railway will automatically detect it's a Laravel app**

## Step 3: Add MySQL Database

1. **In your Railway project dashboard**, click **"+ New"**
2. **Select "Database"** → **"MySQL"**
3. **Railway will create a MySQL database**
4. **Note down the connection details** (they'll be provided automatically)

## Step 4: Configure Environment Variables

In your Railway project dashboard, go to **"Variables"** tab and add:

### Required Environment Variables:
```
APP_NAME=E-Tinda Marketplace
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=pGXBFFWPDEFRaOjHLhiaPRqEwarAAlIL

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Generate APP_KEY:
1. **Run locally**: `php artisan key:generate --show`
2. **Copy the generated key** and add it as `APP_KEY` in Railway variables

**Your generated APP_KEY**: `base64:cRJjFOk9/1ekSHuxevm+1qgaQ2m8iB4Aih2c6wLPyyo=`

## Step 5: Deploy

1. **Railway will automatically deploy** when you push to your main branch
2. **Monitor the deployment** in the Railway dashboard
3. **Check logs** if there are any issues

## Step 6: Database Setup

After successful deployment, you need to run migrations and seeders:

### Option A: Using Railway CLI
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login to Railway
railway login

# Connect to your project
railway link

# Run migrations and seeders
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

### Option B: Using Railway Dashboard
1. **Go to your service** in Railway dashboard
2. **Click on "Deployments"**
3. **Click on the latest deployment**
4. **Open the terminal/console**
5. **Run the commands**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

## Step 7: Verify Deployment

1. **Visit your app URL** (provided by Railway)
2. **Test the application**:
   - Register a new user
   - Login as admin (admin@example.com / admin@example.com)
   - Check if products are loaded
   - Test farmer and buyer functionalities

## Troubleshooting

### Common Issues:

1. **APP_KEY not set**:
   - Generate key: `php artisan key:generate --show`
   - Add to Railway environment variables

2. **Database connection failed**:
   - Verify DB credentials in Railway variables
   - Check if MySQL service is running

3. **Migration errors**:
   - Check database permissions
   - Ensure all migration files are present

4. **Storage permissions**:
   - Railway handles this automatically, but if issues occur:
   ```bash
   railway run chmod -R 755 storage bootstrap/cache
   ```

### Logs and Debugging:
- **View logs**: Railway dashboard → Your service → Logs
- **Enable debug mode temporarily**: Set `APP_DEBUG=true` in variables
- **Check database**: Railway dashboard → MySQL service → Connect

## Post-Deployment

1. **Set up custom domain** (optional):
   - Railway dashboard → Settings → Domains
   - Add your custom domain

2. **Configure SSL** (automatic with Railway)

3. **Set up monitoring**:
   - Railway provides basic monitoring
   - Consider adding external monitoring services

## Environment Variables Reference

| Variable | Value | Description |
|---------|-------|-------------|
| APP_NAME | E-Tinda Marketplace | Application name |
| APP_ENV | production | Environment |
| APP_DEBUG | false | Debug mode |
| APP_URL | https://your-app.up.railway.app | Your Railway URL |
| APP_KEY | (generated) | Laravel encryption key |
| DB_CONNECTION | mysql | Database driver |
| DB_HOST | mysql.railway.internal | Database host |
| DB_PORT | 3306 | Database port |
| DB_DATABASE | railway | Database name |
| DB_USERNAME | root | Database username |
| DB_PASSWORD | (from Railway) | Database password |

## Support

- **Railway Documentation**: https://docs.railway.app
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **Railway Community**: https://discord.gg/railway
