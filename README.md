# STFBackendCodeCallenge

### Inicialización del proyecto
Levantar contenedores
```
make run
```
Una vez levantados ejecutar el composer del contenedor
```
make composer-install
```
Ya estarán disponibles los endpoints propuestos en la tarea
```
http://localhost/booking/maximize
http://localhost/booking/stats
```

### Tests

Estan separados en dos suites, uno para los test unitarios y otro para los test de los endpoints, se pueden lanzar con los siguientes comandos:
```
Unitarios
  make php-unit-unit
  
Endpoints
  make php-unit-api
  
Todas las suites
  make php-unit-all:
```
