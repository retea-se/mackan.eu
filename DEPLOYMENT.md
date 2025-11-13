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

# Git pull (deploy changes)
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "cd ~/public_html/retea/key && git pull origin main"
```
