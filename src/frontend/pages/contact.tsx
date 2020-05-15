import * as React from 'react';
import Head from 'next/head';

import { Formik } from 'formik';
import axios, { AxiosResponse } from 'axios';
import { FaEnvelope, FaPhone } from 'react-icons/fa';

import './contact.scss';

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
      success: false,
    };
  }

  render(): JSX.Element {
    return (
      <div id="contact" className="contact">
        <Head>
          <title>Blog | Contact Page</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content="welcome to my blog, here you may find interesting content about web app development." />
        </Head>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Get in Touch</h2>
              <p>Have questions? feel free to leave me a message.</p>
              <ul className="contact__icons grid-icons">
                <li>
                  <span><FaPhone color="#3fbfae" size="50px" /></span>
                  <p>
                    (+49) 17671577990
                  </p>
                </li>
                <li>
                  <span><FaEnvelope color="#3fbfae" size="50px" /></span>
                  <p>
                    <a href="mailto:eden.reich@gmail.com">eden.reich@gmail.com</a>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
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
                  const response: AxiosResponse = await axios.post('/api/email/send', JSON.stringify(values), { headers: { 'Content-Type': 'application/json;charset=utf-8' }});

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
                  <form action="/api/email/send" method="post" className="contact__form grid-form" noValidate onSubmit={handleSubmit}>
                    <div className="contact__form-group">
                      <input
                        type="text"
                        className={
                          errors.name && touched.name
                          ? "contact__form-control--error"
                          : "contact__form-control"
                        }
                        name="name"
                        placeholder="Your name"
                        onChange={handleChange}
                        onBlur={handleBlur}
                        value={values.name}
                      />
                    </div>
                    <div className="contact__form-group">
                      <input
                        type="email"
                        className={
                          errors.email && touched.email
                          ? "contact__form-control--error"
                          : "contact__form-control"
                        }
                        name="email"
                        placeholder="Your e-mail"
                        onChange={handleChange}
                        onBlur={handleBlur}
                        value={values.email}
                      />
                    </div>
                    <div className="contact__form-group">
                      <input
                        type="text"
                        className="contact__form-control"
                        name="tel"
                        value={values.tel}
                        onChange={handleChange}
                        placeholder="Phone"
                      />
                    </div>
                    <div className="contact__form-group">
                      <textarea
                        name="message"
                        className="contact__form-control"
                        rows={3}
                        value={values.message}
                        onChange={handleChange}
                        placeholder="Type your message here..."
                        required
                      />
                    </div>
                    <button
                        type="submit"
                        className="contact__button"
                        disabled={isSubmitting}
                      >
                      {isSubmitting ? 'Sending...' : 'Send'}
                    </button>
                    <div className={this.state.success ? 'notification-box alert alert-success' : 'notification-box'} >
                        {this.state.success ? 'Thanks! I\'ll reach you out as soon as possible !' : ''}
                    </div>
                  </form>
                )}
              />
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default ContactPage;
