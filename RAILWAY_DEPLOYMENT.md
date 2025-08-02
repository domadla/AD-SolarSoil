# Railway Deployment Guide for SolarSoil

## What Gets Deployed

✅ **Frontend**: All CSS, JavaScript, and images from `assets/` and `pages/*/assets/`  
✅ **Backend**: PHP application with all handlers and utilities  
✅ **Database**: PostgreSQL and MongoDB connections  
✅ **Static Files**: All plant images, logos, and media files  

## Step-by-Step Deployment

### 1. Prepare Your Repository
- Make sure all your changes are committed to GitHub
- Ensure your repository is public or Railway has access to it

### 2. Sign Up for Railway
- Go to [railway.app](https://railway.app)
- Sign up with your GitHub account
- Verify your email

### 3. Create New Project
1. Click "New Project"
2. Select "Deploy from GitHub repo"
3. Choose your SolarSoil repository
4. Railway will automatically detect the Dockerfile and start building

### 4. Add Database Services

#### Add PostgreSQL:
1. In your Railway project dashboard, click "New Service"
2. Select "Database" → "PostgreSQL"
3. Wait for it to provision
4. Copy the connection details (you'll need these for environment variables)

#### Add MongoDB:
1. Click "New Service" again
2. Select "Database" → "MongoDB"
3. Wait for it to provision
4. Copy the connection details

### 5. Configure Environment Variables

In your main application service, go to "Variables" tab and add:

```bash
# Application Settings
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://your-app-name.railway.app

# PostgreSQL Database (replace with your actual values)
DB_HOST=your-postgresql-host.railway.app
DB_PORT=5432
DB_NAME=railway
DB_USER=postgres
DB_PASSWORD=your-postgresql-password

# MongoDB Database (replace with your actual values)
MONGO_HOST=your-mongodb-host.railway.app
MONGO_PORT=27017
MONGO_DB=railway
MONGO_USER=root
MONGO_PASSWORD=your-mongodb-password
```

### 6. Deploy and Test

1. Railway will automatically deploy when you push changes to your repository
2. Wait for the build to complete (usually 2-5 minutes)
3. Click on your application service to get the public URL
4. Test your website at the provided URL

### 7. Custom Domain (Optional)

1. In your application service, go to "Settings" tab
2. Click "Custom Domains"
3. Add your domain and follow the DNS instructions

## File Structure After Deployment

```
Your Website URL/
├── / (Home page)
├── /pages/Home/ (User dashboard)
├── /pages/Shop/ (Plant shop)
├── /pages/Cart/ (Shopping cart)
├── /pages/Order/ (Order management)
├── /pages/Profile/ (User profile)
├── /pages/Admin/ (Admin panel)
└── /pages/About/ (About page)
```

## Troubleshooting

### If the build fails:
1. Check the build logs in Railway dashboard
2. Ensure all files are committed to GitHub
3. Verify the Dockerfile.railway is in your repository

### If the website doesn't load:
1. Check the deployment logs
2. Verify environment variables are set correctly
3. Ensure database services are running

### If database connection fails:
1. Check database connection strings in environment variables
2. Verify database services are provisioned
3. Test database connectivity

## Monitoring

- **Logs**: View real-time logs in Railway dashboard
- **Metrics**: Monitor CPU, memory, and network usage
- **Health Checks**: Railway automatically checks your app's health

## Cost

- Railway offers a free tier with:
  - 500 hours/month of runtime
  - 1GB storage
  - Shared CPU
- Paid plans start at $5/month for more resources

## Support

- Railway documentation: [docs.railway.app](https://docs.railway.app)
- Community Discord: [discord.gg/railway](https://discord.gg/railway) 