#!/bin/bash
df | grep /dev/root | awk -F ' ' '{print $5}' | sed 's/%//' > percent.txt;
