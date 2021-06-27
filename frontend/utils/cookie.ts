export class CookieJar {

  private cookies: string;

  constructor(rawCookies: string) {
    this.cookies = rawCookies;
  }

  get(cookieName: string): string | null {
    if (typeof this.cookies === 'undefined') return;

    const cookies = this.cookies.split(';');

    for (const cookie of cookies) {
      const keyAndValue = cookie.split('=');
      if (keyAndValue[0] === cookieName && typeof keyAndValue[1] !== 'undefined') {
        return keyAndValue[1];
      }
    }

    return null;
  }
}
