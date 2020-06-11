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
      @click="deploySite"
      :class="{
        loading: isLoading,
        success: isSuccess,
        error: isError
      }"
    >
      <span v-if="isLoading">{{ loading }}</span>
      <span v-else-if="isSuccess">{{ complete }}</span>
      <span v-else-if="isError">{{ error }}</span>
      <span v-else>{{ deploy }}</span>
      <div
        v-if="latest && !isLoading && !isSuccess && !isError"
        class="k-vercel-changes"
        :class="{ new: newContent }"
      >
        <span v-if="newContent">{{ siteModified.count }} &uarr;</span>
        <k-icon v-else type="check">
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
    deploy: String,
    loading: String,
    complete: String,
    error: String,
    button: Boolean,
    help: String,
    siteModified: Object
  },
  data() {
    return {
      isLoading: false,
      isError: null,
      isSuccess: false,
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
    deploySite() {
      this.isSuccess = false;
      this.isError = false;
      this.latest = null;
      this.isLoading = true;
      this.$api
        .get("vercel")
        .then(response => {
          var res = JSON.parse(response);
          var job = [];
          job["state"] = res.job.state;
          job["created"] = res.job.createdAt;
          this.latest = job;
          this.isLoading = false;
          this.isError = false;
          this.isSuccess = true;
          setTimeout(() => {
            this.isSuccess = false;
            this.getLatest();
            this.checkSiteModified();
          }, 10000);
        })
        .catch(() => {
          this.isLoading = false;
          this.isError = true;
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
      return dayjs(value).locale('fr').fromNow();
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
    color: #5d800d;
    transform: translate(0, -50%);
    &.new {
      background: #f5871f;
      color: white;
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
