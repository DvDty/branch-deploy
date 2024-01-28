# Branch deploy

This repository holds two code bases:
 - Application, developed by a software team
 - Control plane, that allows easy deployments of the application

## Application

Main focus is to provide the development team strong CI:
 - Secure
 - Flexible
 - Highly automated
 - Fast

|                                                                                                              | Push to main | Push on other branches | Pull requests |
|--------------------------------------------------------------------------------------------------------------|--------------|------------------------|---------------|
| [Gitleaks](https://github.com/DvDty/branch-deploy/blob/main/.github/workflows/main-branch-build.yaml#L8-L26) |       ✅      |            ✅           |       ✅       |
| [Code linter](https://github.com/DvDty/branch-deploy/blob/main/.github/workflows/main-branch-build.yaml#L28-L64)                                                                                              |              |                        |       ✅       |
| Build docker image                                                                                           |       ✅      |            ✅           |       ✅       |
| Push image to Docker Hub                                                                                     |       ✅      |            ✅           |               |
| Scan image before pushing                                                                                    |       ✅      |                        |       ✅       |
| Unit tests                                                                                                   |       ✅      |                        |       ✅       |
| Feature tests                                                                                                |       ✅      |                        |       ✅       |
| Static code analysis                                                                                         |       ✅      |                        |       ✅       |
| Sonar Cloud analysis                                                                                         |       ✅      |                        |       ✅       |
| Database migrations                                                                                          |       ✅      |                        |       ✅       |
| Check code coverage                                                                                          |       ✅      |                        |       ✅       |
| Store code coverage report                                                                                   |       ✅      |                        |               |
| Deploy to minikube                                                                                           |       ✅      |                        |       ✅       |


### Gitleaks
Example with leaked api key:

![img.png](img.png)

Notification in slack:

![img_1.png](img_1.png)

### Code linter
![img_2.png](img_2.png)


![img_3.png](img_3.png)

![img_4.png](img_4.png)