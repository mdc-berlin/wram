# wram - Wer Radelt am meisten

"Bei "Wer radelt am meisten?" geht es um alle Kilometer, die mit dem Rad von den Teilnehmern gefahren werden. Die Kilometer werden zusammengerechnet und mit den anderen teilnehmenden Unternehmen verglichen."

## Info

Dies ist die Intranet-Komponente von WRAM

Was brauchst du?

  * Linux mit Apache (Debian oder CentOS)
  * Mysql
  * Active Directory oder OpenLDAP

## Installation

```
cp ./application/config/database.php.sample ./application/config/database.php
cp ./application/config/radel.php.sample ./application/config/radel.php
# danach anpassen

cp ./db/setup.sql.sample ./db/setup.sql

## hier fehlt nocht ein bisschen was in der doku
```

## Kerberos

### SPN erzeugen

```
yum install mod_auth_kerb
# oder
apt-get install libapache2-mod-auth-kerb
```

```
sudo su

# ticket holen
kinit domain-admin

# krb5 umbiegen und spn anlegen
export KRB5_KTNAME=FILE:/etc/HTTP.keytab
net ads keytab CREATE
net ads keytab ADD HTTP
unset KRB5_KTNAME

# centos
chown apache /etc/HTTP.keytab

# ubuntu
chown www-data /etc/HTTP.keytab
```

### Apache Config

fuege dies zu deiner Apache Site hinzu

Krb5-Only Config

```
  <Location />
      AuthName "Restricted Access"
      AuthType Kerberos
      Krb5Keytab  /etc/HTTP.keytab
      KrbAuthRealms EXAMPLE.COM
      KrbMethodNegotiate On
      KrbMethodK5Passwd Off
      require valid-user
  </Location>
```
Krb5+NTLM-Fallback
```
  <Location />
      AuthName "Restricted Access"
      AuthType Kerberos
      Krb5Keytab  /etc/HTTP.keytab
      KrbAuthRealms EXAMPLE.COM
      KrbMethodNegotiate On
      KrbMethodK5Passwd On
      require valid-user
  </Location>
```
