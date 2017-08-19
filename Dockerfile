FROM linode/lamp
COPY Scripts/run.sh Scripts/run.sh
RUN chmod +x Scripts/run.sh
CMD ["/bin/bash", "-lc", "Scripts/run.sh"]