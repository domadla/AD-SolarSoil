# Docker
## Port Issue
### Option 1
1. Open `compose.yml`
2. Update the first port(external port) in all services or problematic service
```yml
ports:
    # <expose port>:<internal port>
    # Problematic
    - "8000:8000"

    # Updated
    - "8080:8000"
```
3. rerun `compose up`

### Option 2
1. Open docker desktop
2. find the port that is open
3. close it
4. rerun `compose up`

## Docker App
### VM Utilization Issue (For Intel User Common Issue)
1. Open Bios
2. Look for `virtualization`
3. Swich on
4. `Apply and Save`
5. Restart PC

# Database
## Auto Increment Issue
1. Check Run `Auto Increment Script`
2. Create new query
3. Copy the script and modify the needed data: `table` or `column`