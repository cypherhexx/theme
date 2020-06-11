# Version Control

> Simple overview of how we use BitBucket

Do not commit the entire codebase in one go! It makes reviews impossible, you therefore should make regular commits. 


## Branches

We have a convention for branches within our Version Control

| Branch | Description |
|----------|-------------|
| master | This should be an exact replica of production, it is never deployed to production, instead a pull request from ```production``` is merged into this branch after depliyment |
| production | This should be an exact replica of production, this branch is always deployed to the production server, then merged into ```master```. |
| staging | This is where your feature branch or bug fix is mereged into, before beling depliyed to the staging server. Staging is NEVER merged into production! |
| feature-name-of-feature | If you're working on a new feature then you should be using a ```feature``` branch. This branch is merged into ```staging```, then ```production```. |
| bugfix-name-of-bug | If you're working on fixing a bug then you should be using a ```bugfix``` branch. This branch is merged into ```staging```, then ```production```. |

If you are working on a new project which currently isn't live you can remain on the master branch, otherwise you should always be working from either a feature or bugfix branch.
