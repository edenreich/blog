import * as React from 'react';
import Head from 'next/head';
import Link from 'next/link';

import styles from './projects.module.scss';
import Section from '@/components/Section';

interface IProps {
  children?: React.ReactNode;
  route: string;
}

class ProjectsPage extends React.Component<IProps> {

  render(): JSX.Element {
    const title = 'Blog | Projects';
    const description = 'Welcome to my blog, I\'ll be posting about web app development, native apps, DevOps and more.';

    return (
      <div id="projects" className={styles.projects}>
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
          <h2>Projects</h2>
          <p>List of projects</p>
        </Section>
        <Section>
          <div className={styles.projects__list}>
            <div className="card">
              <Link href="https://github.com/edenreich/console-component">
                <a target="_blank">
                  <div className="card__teaser">
                    <img src="pictures/projects/console_component.jpg" />
                  </div>
                  <h3>Console Component</h3>
                  <p>
                    An easy to use component for building powerful console applications written in C++
                  </p>
                </a>
              </Link>
            </div>
            <div className="card">
              <Link href="https://github.com/edenreich/gke-terraform">
                <a target="_blank">
                  <div className="card__teaser">
                    <img src="pictures/projects/terraform_gke.png" />
                  </div>
                  <h3>Terraform a GKE Cluster</h3>
                  <p>
                    Minimalistic terraform module for deploying a GKE cluster
                  </p>
                </a>
              </Link>
            </div>
          </div>
        </Section>
      </div>
    );
  }

}

export default ProjectsPage;
