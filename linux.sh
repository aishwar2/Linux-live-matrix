#!/bin/bash
set -e

# Define servers
servers=("172.17.0.8" "172.17.0.7" "172.17.0.6" "172.17.0.5" "172.17.0.4" "172.17.0.2" "172.17.0.3") # replace with actual IPs
user="root" # replace with your username

# MySQL credentials
mysql_user="root"
mysql_password="2539"
mysql_db="linux_db"
mysql_table="metrics"

# Initialize the data file
data_file="/home/data.csv" #set the location of the csv file
echo "Server,CPU,RAM,Network,Date,Time" > $data_file

while true; do
    # Collect data
    for server in "${servers[@]}"; do
        cpu=$(ssh $user@$server "top -bn1 | grep 'Cpu(s)' | awk '{print 100 - \$8}'")
        ram=$(ssh $user@$server "free -m | awk 'NR==2{printf \"%.2f\", \$3*100/\$2 }'")
        network=$(ssh $user@$server "ifstat -i eth0 1 1 | awk 'NR==3{print \$1}'") # replace ens33 with your network interface
        date=$(date '+%Y-%m-%d')
        time=$(date '+%H:%M:%S')
        echo "$server,$cpu,$ram,$network,$date,$time" >> $data_file

        # Insert data into MySQL
        mysql --defaults-file=~/.my.cnf $mysql_db <<EOF
        INSERT INTO $mysql_table (server, cpu, ram, network, date, time) VALUES ('$server', '$cpu', '$ram', '$network', '$date', '$time');
EOF
    done
    sleep 4  # Wait for 4 seconds before running the loop again
done
