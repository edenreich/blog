terraform {
  required_version = ">= 0.14.5"

  backend "gcs" {
    bucket = "eden-reich.com"
    prefix = "terraform/state"
  }
}

provider "google" {
  project = "eden-266620"
  region  = "europe-west1"
}
