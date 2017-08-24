FROM linode/lamp
RUN apt-get install php5-redis
COPY Scripts/run.sh Scripts/run.sh
RUN chmod +x Scripts/run.sh
CMD ["/bin/bash", "-lc", "Scripts/run.sh"]