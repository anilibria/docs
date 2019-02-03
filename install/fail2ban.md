```
apt-get install fail2ban
```

Редактируем файл `/etc/fail2ban/jail.conf`, включаем мониторинг `ssh` и `pure-ftpd`

```
[ssh]

enabled  = true
port     = ssh
filter   = sshd
logpath  = /var/log/auth.log
maxretry = 6

[pure-ftpd]

enabled  = true
port     = ftp,ftp-data,ftps,ftps-data
filter   = pure-ftpd
logpath  = /var/log/syslog
maxretry = 6
```

Ввожу шесть раз неверный пароль.
```
# ssh root@5.9.82.141
root@5.9.82.141's password: 
Permission denied, please try again.
root@5.9.82.141's password: 
Permission denied, please try again.
root@5.9.82.141's password: 
Permission denied (publickey,password).

# ssh root@5.9.82.141
root@5.9.82.141's password: 
Permission denied, please try again.
root@5.9.82.141's password: 
Permission denied, please try again.
root@5.9.82.141's password: 
Permission denied (publickey,password).
```

Fail2ban блокирует подключение.
```
# ssh root@5.9.82.141
ssh: connect to host 5.9.82.141 port 22: Connection refused
```

Проверяю логи, правила iptables.
```
# cat /var/log/auth.log | grep 5.39.64.7
Feb  1 14:16:27 x sshd[29355]: Failed password for root from 5.39.64.7 port 60397 ssh2
Feb  1 14:16:31 x sshd[29355]: Failed password for root from 5.39.64.7 port 60397 ssh2
Feb  1 14:16:38 x sshd[29355]: Failed password for root from 5.39.64.7 port 60397 ssh2
Feb  1 14:16:38 x sshd[29355]: Connection closed by 5.39.64.7 [preauth]
Feb  1 14:16:42 x sshd[29360]: Failed password for root from 5.39.64.7 port 60931 ssh2
Feb  1 14:16:45 x sshd[29360]: Failed password for root from 5.39.64.7 port 60931 ssh2
Feb  1 14:16:48 x sshd[29360]: Failed password for root from 5.39.64.7 port 60931 ssh2
Feb  1 14:16:48 x sshd[29360]: Connection closed by 5.39.64.7 [preauth]
```
```
# cat /var/log/fail2ban.log | grep 5.39.64.7
2018-02-01 14:16:49,691 fail2ban.actions[1502]: WARNING [ssh] Ban 5.39.64.7
```
```
# iptables -L fail2ban-ssh -v -n
Chain fail2ban-ssh (1 references)
 pkts bytes target     prot opt in     out     source               destination         
    1    60 REJECT     all  --  *      *       5.39.64.7            0.0.0.0/0            reject-with icmp-port-unreachable
  14M   21G RETURN     all  --  *      *       0.0.0.0/0            0.0.0.0/0           
```
