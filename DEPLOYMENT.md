## Deployment Workflow

Production deployments are performed over SSH using the existing key-based access. No passwords are required.

### Scripted Deployment

Run `powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1` to pull the latest code on the production server. Optional switches:

- `-StatusOnly` – check the current Git status (with optional `-TailLog`).
- `-TailLog` – display the latest 50 lines from `error_log`.
- `-DryRun` – print the remote commands without executing them.

### SSH Key Location

```
C:\Users\marcu\.ssh\id_rsa_pollify
```

### GitHub Token Configuration (Production Server)

If the production server needs to authenticate with GitHub using a token (instead of SSH keys), configure it as follows:

1. **SSH to the production server:**
   ```bash
   "C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se
   ```

2. **Navigate to the repository directory:**
   ```bash
   cd ~/public_html/retea/key
   ```

3. **Configure Git to use HTTPS with token:**
   ```bash
   git remote set-url origin https://github.com/tempdump/mackan-eu.git
   ```

4. **Set up Git credential helper to store the token:**
   ```bash
   git config --global credential.helper store
   ```

5. **Configure the token for this repository:**
   ```bash
   # This will prompt for username and password
   # Username: tempdump (or your GitHub username)
   # Password: <your_github_token> (use token, not password!)
   git fetch origin main
   ```

   **OR** create a `.git-credentials` file manually:
   ```bash
   echo "https://<username>:<token>@github.com" > ~/.git-credentials
   chmod 600 ~/.git-credentials
   ```

   **OR** use environment variable (recommended for automation):
   ```bash
   export GIT_ASKPASS=echo
   export GIT_USERNAME=tempdump
   export GITHUB_TOKEN=<your_token_here>
   git -c credential.helper='!f() { echo username=$GIT_USERNAME; echo password=$GITHUB_TOKEN; }; f' pull origin main
   ```

6. **Verify the configuration:**
   ```bash
   git remote -v
   git fetch origin main
   ```

**Note:** Replace `<token>` with your actual GitHub Personal Access Token. The token should have `repo` scope for private repositories, or `public_repo` for public repositories.

### Common SSH Commands

```bash
# Connect to server
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se

# Copy file to server
"C:\Windows\System32\OpenSSH\scp.exe" "local-file.php" mackaneu@omega.hostup.se:~/public_html/retea/key/

# Execute remote command
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "cd ~/public_html/retea/key && git status"

# View error log
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "tail -50 ~/public_html/retea/key/error_log"

# Git pull (deploy changes) - using SSH
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "cd ~/public_html/retea/key && git pull origin main"

# Git pull (deploy changes) - using HTTPS with token
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "cd ~/public_html/retea/key && GITHUB_TOKEN=<token> git -c credential.helper='!f() { echo username=tempdump; echo password=$GITHUB_TOKEN; }; f' pull origin main"
```

### Alternative: Using GitHub Actions

Deployment can also be automated via GitHub Actions. See `.github/workflows/deploy.yml` for the workflow configuration.

**Required GitHub Secrets:**
- `SSH_HOST` – `omega.hostup.se`
- `SSH_USER` – `mackaneu`
- `SSH_PRIVATE_KEY` – SSH private key content
- `DEPLOY_PATH` – `~/public_html/retea/key` (or `/home/mackaneu/public_html/retea/key`)

See `GITHUB_SECRETS_INSTRUCTIONS.md` for detailed setup instructions.
