terraform {
  backend "gcs" {
    bucket  = "eden-reich.com-tf-state"
    prefix  = "terraform/state"
  }
}