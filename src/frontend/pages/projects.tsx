import * as React from 'react';
import Head from 'next/head';
import Link from 'next/link';

import './projects.scss';

interface IProps {
  children?: React.ReactNode;
  route: string;
}

class ProjectsPage extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <div id="projects" className="projects">
        <Head>
          <title>Blog | Projects Page</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content="welcome to my blog, here you may find interesting content about web app development." />
        </Head>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Projects</h2>
              <p>List of projects</p>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column projects__list">
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
              {/* <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div>
              <div className="card"></div> */}
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default ProjectsPage;
