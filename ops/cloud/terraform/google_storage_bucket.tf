resource "google_storage_bucket_access_control" "public_rule" {
  bucket = "eden-reich-com-assets"
  role   = "READER"
  entity = "allUsers"
}

resource "google_storage_bucket" "assets" {
  name          = "eden-reich-com-assets"
  location      = "europe-west1"
  force_destroy = true

  uniform_bucket_level_access = false

  cors {
    origin          = ["*"]
    method          = ["GET", "HEAD", "PUT", "POST", "DELETE"]
    response_header = ["*"]
    max_age_seconds = 3600
  }
}
