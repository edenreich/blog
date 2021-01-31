provider "google" {
  project     = "eden-266620"
  region      = "europe-west1"
}
resource "google_storage_bucket" "assets" {
  name          = "eden-reich.com"
  location      = "europe-west1"
  force_destroy = true

  uniform_bucket_level_access = true

  cors {
    origin          = ["*"]
    method          = ["GET", "HEAD", "PUT", "POST", "DELETE"]
    response_header = ["*"]
    max_age_seconds = 3600
  }
}