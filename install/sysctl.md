```
# nano /etc/sysctl.conf

net.ipv4.ip_forward=0
net.ipv4.tcp_syncookies=0

net.ipv4.icmp_echo_ignore_all=1

net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1
net.ipv6.conf.lo.disable_ipv6 = 1

net.ipv4.ip_local_port_range = 10000 65535

net.ipv4.tcp_fin_timeout = 4
net.ipv4.tcp_keepalive_time = 60
net.ipv4.tcp_max_syn_backlog = 10240
net.core.somaxconn = 1024

# sysctl -p
```


