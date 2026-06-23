# Design Spec: Admin Login Rate Limiting

## Overview
This specification details the design for an IP-based rate limiting mechanism for the admin login interface. The goal is to prevent brute-force attacks by limiting login attempts per IP address.

## Architecture
We will introduce a database-based tracking mechanism.

### Data Structure
- New table: `login_attempts`
  - `id`: INT (Primary Key)
  - `ip_address`: VARCHAR(45)
  - `attempt_time`: TIMESTAMP

### Logic Flow
1. **Attempt Check**: On every login POST request:
   - Query `login_attempts` for current IP within the last 30 minutes.
   - If count >= 5, block attempt with "Too many failed attempts" message.
2. **Handle Failure**: If password verification fails:
   - Insert new row into `login_attempts` with current IP.
3. **Handle Success**: If credentials valid:
   - Delete all rows from `login_attempts` matching current IP.

## Security Considerations
- IPs must be sanitized before database insertion to prevent injection (though PDO is used).
- The 30-minute window balances security and user convenience.

## Testing
- Simulate 5 failed logins and verify subsequent access is blocked.
- Verify successful login clears the attempt counter.
