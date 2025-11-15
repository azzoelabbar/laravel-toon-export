# Publishing Guide

## Current Status

✅ **Package is ready for publishing!**

- Vendor name: `azzoelabbar/laravel-toon-export`
- Version: `0.1.0`
- License: MIT
- All files are in place

## Steps to Publish on Packagist

### 1. Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `laravel-toon-export`
3. Description: `TOON exporters for Laravel (collections, models, queries) - optimized for LLM/token usage`
4. Choose **Public** (recommended) or **Private**
5. **Don't** initialize with README (you already have one)
6. Click "Create repository"

> **Note**: If you choose **Private**, see the "Private Repository Setup" section below for installation instructions.

### 2. Push Package to GitHub

From the package directory:

```bash
cd packages/egate/laravel-toon-export

# Initialize git if not already done
git init

# Add all files
git add .

# Commit
git commit -m "Initial release v0.1.0"

# Add remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/laravel-toon-export.git

# Push
git branch -M main
git push -u origin main
```

### 3. Create a Release Tag

```bash
# Tag the release
git tag -a v0.1.0 -m "Release version 0.1.0"

# Push the tag
git push origin v0.1.0
```

Or create a release on GitHub:
1. Go to your repo → Releases → "Create a new release"
2. Tag: `v0.1.0`
3. Title: `v0.1.0`
4. Description: `Initial release with TOON export functionality`
5. Click "Publish release"

### 4. Submit to Packagist

1. Go to https://packagist.org/packages/submit
2. Login with GitHub (if not already)
3. Paste your GitHub repository URL:
   ```
   https://github.com/YOUR_USERNAME/laravel-toon-export
   ```
4. Click "Check" then "Submit"

### 5. Enable Auto-Update (Recommended)

1. Go to your package page on Packagist
2. Click "Settings"
3. Enable "GitHub Service Hook"
4. This will auto-update Packagist when you push new tags

## After Publishing

### If Published on Packagist (Public Repo)

Once published, users can install it with:

```bash
composer require azzoelabbar/laravel-toon-export
```

### If Using Private GitHub Repository (Not on Packagist)

If your repository is **private** and you want to use it without publishing to Packagist, you need to configure Composer authentication:

#### Step 1: Create GitHub Personal Access Token

1. Go to https://github.com/settings/tokens
2. Click "Generate new token" → "Generate new token (classic)"
3. Give it a name: `Composer Package Access`
4. Select scopes:
   - ✅ `repo` (Full control of private repositories)
   - ✅ `read:packages` (Download packages from GitHub Package Registry)
5. Click "Generate token"
6. **Copy the token immediately** (you won't see it again!)

#### Step 2: Configure Composer with GitHub Token

```bash
composer config --global github-oauth.github.com YOUR_GITHUB_TOKEN
```

Verify it's set:
```bash
cat ~/.composer/auth.json
```

You should see:
```json
{
  "github-oauth": {
    "github.com": "YOUR_GITHUB_TOKEN"
  }
}
```

#### Step 3: Add VCS Repository to Your Project

In your Laravel project's `composer.json`, add:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/azzoelabbar/laravel-toon-export"
    }
  ],
  "require": {
    "azzoelabbar/laravel-toon-export": "@dev"
  }
}
```

#### Step 4: Install the Package

```bash
composer require azzoelabbar/laravel-toon-export:@dev
```

#### Troubleshooting Private Repos

If you get `remote: Repository not found`:

- ✅ Check that your GitHub token has `repo` scope
- ✅ Verify the token is set: `composer config --global github-oauth.github.com`
- ✅ Make sure the VCS repository is added in `composer.json`
- ✅ Verify the repository URL is correct
- ✅ Try regenerating the token if it's expired

#### Recommendation

**For easiest setup**: Make the repository **Public**
- No token configuration needed
- Works on all machines (CI/CD, production, etc.)
- Can be published to Packagist later
- Zero authentication headaches

**Use Private only if**:
- Package contains sensitive code
- You want to restrict access
- You're okay with token management on every machine

## Important Notes

⚠️ **Update Email**: Change the email in `composer.json` from `azzoelabbar2002@gmail.com` to your real email before publishing.

⚠️ **Namespace**: The package uses `Egate\ToonExport` namespace. If you want to change it to `Azzoelabbar\ToonExport`, you'll need to:
- Update all `namespace` declarations in PHP files
- Update `composer.json` autoload section
- Update service provider references

## Future Releases

For new versions:

1. Update version in `composer.json`
2. Update CHANGELOG.md (create one if needed)
3. Commit changes
4. Create new tag: `git tag -a v0.2.0 -m "Release v0.2.0"`
5. Push tag: `git push origin v0.2.0`
6. Packagist will auto-update (if hook enabled)

