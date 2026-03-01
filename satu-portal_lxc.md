# Proxmox LXC CLI Deployment Plan

This plan provides the exact commands to run in the Proxmox host shell to create and initialize the LXC container for the "satu" project.

## User Review Required

> [!IMPORTANT]
> Ganti `[PASSWORD]` dengan password root yang Anda inginkan.
> Ganti `[BRIDGE]` jika nama bridge Anda bukan `vmbr0`.
> Pastikan template Debian 12 sudah di-download di Proxmox Anda.

## CLI Commands (Run on Proxmox Host)

### 1. Create LXC Container
Gunakan ID `100` (atau ganti jika sudah terpakai):

```bash
# Download template (jika belum ada)
pveam update
pveam download local debian-13-standard_13.1-2_amd64.tar.zst

# Create Container
pct create 100 local:vztmpl/debian-13-standard_13.1-2_amd64.tar.zst \
  --hostname satu-portal \
  --cores 1 \
  --memory 1024 \
  --swap 512 \
  --net0 name=eth0,bridge=vmbr0,firewall=1,ip=dhcp,type=veth \
  --rootfs local-lvm:10 \
  --password [PASSWORD] \
  --unprivileged 1 \
  --features nesting=1

# Start Container
pct start 100
```

### 2. Enter and Configure
Jalankan perintah ini untuk masuk ke dalam container dan menginstal stack:

```bash
pct exec 121 -- bash -c "
  apt update && apt upgrade -y && \
  apt install nginx mariadb-server php-fpm php-mysql php-curl php-gd php-mbstring git -y && \
  cd /var/www && \
  git clone https://github.com/rsdatusanggul/satu.git && \
  cd satu && \
  cp .env.example .env && \
  chown -R www-data:www-data /var/www/satu && \
  chmod -R 755 /var/www/satu
"
```

## Verification Plan

### Manual Verification
1.  **Check IP**: Cari tahu IP container dengan `pct exec 100 -- ip a`.
2.  **Access Web**: Buka IP tersebut di browser.
