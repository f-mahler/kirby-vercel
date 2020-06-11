<template>
  <div class="k-vercel">
    <div class="k-vercel-label">
      <k-headline>{{ label }}</k-headline>
      <div v-if="latest" class="k-vercel-latest">
        <span :data-status="latest.state"></span>{{ latest.created | date }}
      </div>
    </div>
    <div
      v-if="button"
      class="k-vercel-button"
      @click="deploy"
      :class="{
        loading: loading,
        success: success,
        error: error
      }"
    >
      <span v-if="loading">Deploying...</span>
      <span v-else-if="success">Complete</span>
      <span v-else-if="error">Failed</span>
      <span v-else>Deploy</span>
      <div
        v-if="latest && !loading && !success && !error"
        class="k-vercel-changes"
        :class="{ new: newContent }"
      >
        <span v-if="newContent"
          >{{ siteModified.count }} new change<span
            v-if="siteModified.count > 1"
            >s</span
          ></span
        >
        <span v-else>LATEST</span>
      </div>
    </div>
    <div
      v-if="help"
      data-theme="help"
      class="k-vercel-help k-text k-field-help"
      v-html="help"
    />
  </div>
</template>

<script>
import dayjs from "dayjs";
var relativeTime = require("dayjs/plugin/relativeTime");
export default {
  props: {
    label: String,
    button: Boolean,
    help: String,
    siteModified: Object
  },
  data() {
    return {
      loading: false,
      error: null,
      success: false,
      latest: null,
      newContent: false
    };
  },
  created() {
    this.getLatest();
  },
  methods: {
    checkSiteModified() {
      var latestDeploy = this.latest.created.toString().slice(0, -3);
      if (this.siteModified.timestamp > parseInt(latestDeploy)) {
        this.newContent = true;
      } else {
        this.newContent = false;
      }
    },
    deploy() {
      this.success = false;
      this.error = false;
      this.latest = null;
      this.loading = true;
      this.$api
        .get("vercel")
        .then(response => {
          var res = JSON.parse(response);
          var job = [];
          job["state"] = res.job.state;
          job["created"] = res.job.createdAt;
          this.latest = job;
          this.loading = false;
          this.error = false;
          this.success = true;
          setTimeout(() => {
            this.success = false;
            this.getLatest();
            this.checkSiteModified();
          }, 10000);
        })
        .catch(() => {
          this.loading = false;
          this.error = true;
        });
    },
    getLatest() {
      this.$api
        .get("vercel/latest")
        .then(response => {
          var res = JSON.parse(response);
          this.latest = res.deployments[0];
          this.checkSiteModified();
        })
        .catch(e => {
          console.log(e);
        });
    }
  },
  filters: {
    date(value) {
      if (!value) return "";
      dayjs.extend(relativeTime);
      return dayjs(value).fromNow();
    }
  }
};
</script>

<style lang="scss">
.k-vercel-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.k-vercel-button {
  margin-top: 0.75rem;
  padding: 1rem;
  cursor: pointer;
  background: white;
  box-shadow: 0 2px 5px rgba(22, 23, 26, 0.05);
  transition: background 0.5s, color 0.5s;
  position: relative;
  .k-vercel-changes {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.02rem;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    position: absolute;
    right: 1rem;
    top: 50%;
    background: #5d800d;
    color: white;
    transform: translate(0, -50%);
    &.new {
      background: #f5871f;
    }
  }
  &:before {
    content: "▲";
    padding-right: 0.5rem;
  }
  &:hover {
    background: rgba(255, 255, 255, 0.6);
  }
  &.loading {
    background: black !important;
    color: white !important;
  }
  &.success {
    background: #5d800d !important;
    color: white !important;
  }
  &.error {
    background: #c82829 !important;
    color: white !important;
  }
  &.new {
    background: #f5871f;
    color: black;
  }
}
.k-vercel-latest {
  color: #777;
  font-size: 0.875rem;
  span {
    width: 0.5rem;
    height: 0.5rem;
    background: #777;
    display: inline-block;
    transform: translateY(-0.05rem);
    border-radius: 50%;
    margin-right: 0.5rem;
    margin-left: 0.5rem;
    &[data-status="QUEUED"] {
    }
    &[data-status="BUILDING"] {
      background: #f5871f;
    }
    &[data-status="READY"] {
      background: #5d800d;
    }
    &[data-status="ERROR"] {
      background: #c82829;
    }
  }
}
</style>
