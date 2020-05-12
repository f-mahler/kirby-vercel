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
      :class="{ loading: loading, success: success, error: error }"
    >
      <span v-if="loading">Deploying...</span>
      <span v-else-if="success">Complete</span>
      <span v-else-if="error">An error occured</span>
      <span v-else>Deploy</span>
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
    help: String
  },
  data() {
    return {
      loading: false,
      error: null,
      success: false,
      latest: null
    };
  },
  created() {
    this.getLatest();
  },
  methods: {
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
  &:before {
    content: "▲";
    padding-right: 0.5rem;
  }
  &:hover {
    background: rgba(255, 255, 255, 0.6);
  }
  &.loading {
    background: black;
    color: white;
  }
  &.success {
    background: #5d800d;
    color: white;
  }
  &.error {
    background: #c82829;
    color: white;
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
