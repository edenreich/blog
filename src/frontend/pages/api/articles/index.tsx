import { NextApiRequest, NextApiResponse  } from 'next';
import { uuid } from 'uuidv4';
import { Article } from '@/interfaces/article';


interface ResponseInterface {
    success: boolean;
    debug?: any;
}

export default async (_req: NextApiRequest, res: NextApiResponse<ResponseInterface>) => {

    const articles: Article[] = [
        {
            id: uuid(),
            title: 'Build your own Kubernetes Cluster (K3S)',
            content: 'Creating your own kubernetes cluster on raspberry pi doesn\'t take much of a setup, in this article i\'m going to walk you throught the process of having a production ready environment at your home',
            link: '/build-your-own-kubernetes-cluster-with-k3s',
            published_at: 'not published yet',
            updated_at: new Date,
            created_at: new Date
        }
    ];


    return res.end(JSON.stringify(articles));
}
