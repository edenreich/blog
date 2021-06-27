import * as React from 'react';
import Link from 'next/link';
import Head from 'next/head';

import { Formik } from 'formik';
import axios, { AxiosResponse } from 'axios';
import { FaEnvelope } from 'react-icons/fa';

import styles from './contact.module.scss';
import Section from '@/components/Section';

interface FieldsInterface {
  name?: string;
  email?: string;
  tel?: string;
  message?: string;
}

interface IState {
  success: boolean;
}

interface IProps {
  children?: React.ReactNode;
  route: string;
}

class ContactPage extends React.Component<IProps, IState> {

  constructor(props: IProps) {
    super(props);

    this.state = {
      success: false
    };
  }

  render(): JSX.Element {
    const title = 'Blog | Contact';
    const description = 'Welcome to my blog, I\'ll be posting about web app development, native apps, DevOps and more.';

    return (
      <div id="contact" className="contact grid-contact">
        <Head>
          <title>{title}</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content={description} />
          <meta property="og:site_name" content="Eden Reich" />
          <meta property="og:title" content={title} />
          <meta property="og:image" content="/pictures/profile_600.png" />
          <meta property="og:description" content={description} />
        </Head>
        <Section>
          <h2>Get in Touch</h2>
          <p>Have questions? feel free to leave me a message.</p>
          <ul className={`${styles.contact__icons}`}>
            <li>
              <span><FaEnvelope color="#3fbfae" size="50px" /></span>
              <p>
                <Link href="mailto:eden.reich@gmail.com">
                  <a>eden.reich@gmail.com</a>
                </Link>
              </p>
            </li>
          </ul>
        </Section>
        <Section>
          <Formik
            initialValues={{ name: '', email: '', tel: '', message: ''}}
            validate={values => {
              const errors: FieldsInterface = {};

              if (!values.name) {
                errors.name = 'Required';
              }

              if (!values.email) {
                errors.email = 'Required';
              } else if (
                !/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test(values.email)
              ) {
                errors.email = 'Invalid email address';
              }

              return errors;
            }}
            onSubmit={async (values, { resetForm, setSubmitting }) => {
              const response: AxiosResponse = await axios.post(`/api/email/send`, JSON.stringify(values), { headers: { 'Content-Type': 'application/json;charset=utf-8' }});

              if (response.data.success) {
                this.setState({
                  success: true
                });
                resetForm();
              }

              setSubmitting(false);
            }}
            render={({
              touched,
              errors,
              values,
              isSubmitting,
              handleChange,
              handleBlur,
              handleSubmit,
            }) => (
              <form action={'/api/email/send'} method="post" className={styles.contact__form} noValidate onSubmit={handleSubmit}>
                <div>
                  <input
                    type="text"
                    className={
                      errors.name && touched.name
                      ? 'form-control--error'
                      : 'form-control'
                    }
                    name="name"
                    placeholder="Your name"
                    onChange={handleChange}
                    onBlur={handleBlur}
                    value={values.name}
                  />
                </div>
                <div className={styles.contact__form__group}>
                  <input
                    type="email"
                    className={
                      errors.email && touched.email
                      ? 'form-control--error'
                      : 'form-control'
                    }
                    name="email"
                    placeholder="Your e-mail"
                    onChange={handleChange}
                    onBlur={handleBlur}
                    value={values.email}
                  />
                </div>
                <div className={styles.contact__form__group}>
                  <input
                    type="text"
                    className="form-control"
                    name="tel"
                    value={values.tel}
                    onChange={handleChange}
                    placeholder="Phone"
                  />
                </div>
                <div className={styles.contact__form__group}>
                  <textarea
                    name="message"
                    className="form-control"
                    rows={3}
                    value={values.message}
                    onChange={handleChange}
                    placeholder="Type your message here..."
                    required
                  />
                </div>
                <div className={`${styles.contact__button} ${styles.contact__form__group}`}>
                  <button
                      type="submit"
                      className="form-button"
                      disabled={isSubmitting}
                    >
                    {isSubmitting ? 'Sending...' : 'Send'}
                  </button>
                </div>
                {
                  this.state.success && <div className={`${styles.contact__notification}`} >
                    Thanks! I'll reach you out as soon as possible !
                  </div>
                }
              </form>
            )}
          />
        </Section>
      </div>
    );
  }

}

export default ContactPage;
