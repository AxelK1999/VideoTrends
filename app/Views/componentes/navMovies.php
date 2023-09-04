<nav>
    <ul class="nav nav-tabs justify-content-center flex-column flex-sm-row" id="nav-movies">
        <li class="nav-item me-5">
            <a class="nav-link active" id="recommended" aria-current="page" href="#" onclick="navigability('recommended')">Recomandadas</a>
        </li>
        <li class="nav-item me-5">
            <a class="nav-link" id="find" href="#" onclick="navigability('find')">Buscar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"id="library" onclick="navigability('library')" >Mi Biblioteca</a>
        </li>
    </ul>
</nav>

<section>
    <div id="spinner" class="spinner-border text-primary mt-5 mx-auto" role="status" style="display: none;">
        <span class="visually-hidden">Loading...</span>
    </div>

    <div id="container-find-movies" class="visually-hidden">

        <div class="input-group mb-3 mt-4 w-75 mx-auto" id="search-bar">
        <button class="btn btn-outline-secondary dropdown-toggle" id="btn-categories" type="button" data-bs-toggle="dropdown" aria-expanded="false">Genero</button>
            <ul class="dropdown-menu container-categories">
                <li><a class="dropdown-item category" href="#">action</a></li>
                <li><a class="dropdown-item category" href="#">anime</a></li>
                <li><a class="dropdown-item category" href="#">science-fiction</a></li>
                <li><a class="dropdown-item category" href="#">fantasy</a></li>
                <li><a class="dropdown-item category" href="#">crime</a></li>
                <li><a class="dropdown-item category" href="#">thriller</a></li>
                <li><a class="dropdown-item category" href="#">superhero</a></li>
                <li><a class="dropdown-item category" href="#">adventure</a></li>
                <li><a class="dropdown-item category" href="#">adventure</a></li>
                <li><a class="dropdown-item category" href="#">family </a></li>
                <li><a class="dropdown-item category" href="#">comedy</a></li>
            </ul>
            <input type="text" class="form-control" id="input-find" aria-label="Text input with dropdown button">
            <button class="btn btn-outline-secondary" type="button" id="btn-find" aria-expanded="false">Buscar</button>
        </div>

        <div id="container-result-find" class="mb-5"> </div>

    </div>

    <div id="container-recommended-movies" class="mb-5"> </div>
    <div id="container-library-movies" class="visually-hidden mb-5"> </div>
</section>

<!-- ----------Componente Item Movie-------------------  -->
<script>

    function itemMovie(movie , comentarios){
        
        let genero = '';

        if(movie.genres){
            movie.genres.forEach((genere , i)=>{
                genero += " "+genere+" ";
            });
        }
        
        let comentariosHTML="";

        comentarios.forEach((comentario , i)=>{

        const fecha = comentario.created_at;
        const fechaObjeto = new Date(fecha);
        const dia = fechaObjeto.getDate();
        const mes = fechaObjeto.getMonth() + 1; 
        const anio = fechaObjeto.getFullYear();

        comentariosHTML+=`
            <div class="comentario">
                <span class="usuario">${comentario.user.name}</span>
                <span class="fecha mx-2">${dia}-${mes}-${anio}</span>
                <p class="contenido">${comentario.comment}</p>
            </div>
            <hr>\n
            `;
                    
        });
        
       let movieHTML = `
            <div class="mt-3 mx-sm-0 me-sm-0 mx-md-5 me-md-5 mb-2 border" id="item-movie" key = "${movie.ids.trakt}">
                
                <div class="row">
                    <div class="col mt-4 text-center">
                        <p class="h6">${movie.title}</p>
                        <p class="h6 mx-2">${movie.year}</p>
                    </div>

                    <div class="col mt-4 text-center">
                        <p class="h6 mx-2">Genero:</p>
                        <p class="h6">[ ${genero} ]</p>
                    </div>

                    <div class="col mt-4 text-center">
                        <p class="h6">Clasificacion:</p>
                        <p class="h6">${movie.certification}</p>
                    </div>

                    <div class="col mt-4 text-center">
                        <p class="h6">Rating:</p>
                        <p class="h6 me-2">⭐${movie.rating}</p>
                    </div>
                </div>

                <hr>

                <div class="row flex-column flex-sm-row">
                    
                    <div class="col text-center" id="container-btn-addMovie">
                        <p><a href="#" class="link-success h6" id='btn-addMovie' style="text-decoration: none;">Añadir a biblioteca ✚</a></p>
                    </div>

                    <div class="col text-center">
                            
                        <div class="accordion text-center" id="accordion">

                            <div class="accordion-item border border-0">
                                <h6 class="accordion-header">
                                    <a class="mt-0 mb-2 link-secondary btn-outline-light " style="text-decoration: none;" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne${movie.ids.trakt}" aria-expanded="true" aria-controls="collapseOne${movie.ids.trakt}">
                                        Comentarios ⯯
                                    </a>
                                </h6>

                                <div id="collapseOne${movie.ids.trakt}" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                    <div class="accordion-body" id="container-comentarios">
                                    
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div> `;



       let contenedor = document.createElement('div');
       contenedor.innerHTML = movieHTML;
       let itemMovie = contenedor.firstElementChild;
       itemMovie.querySelector('#container-comentarios').innerHTML += comentariosHTML;

       return itemMovie;

    }

    function getCommentsMovie(id) {

        return new Promise((resolve, reject) => {

            fetch('<?= BASE_URL.'api/1.0/user/findCommentsById?idMovie=' ?>'+id, {
                method: 'GET',
            })
            .then(res => res.json())
            .then(result => resolve(result.data))
            .catch(err => reject(err));
            
        });
    }

    cargarRecommendedMovies();
    cargarMyLibrary();
    setEventToBtnFind();
    
    let recmendation = document.querySelector("#nav-movies #recommended");
    let library = document.querySelector("#nav-movies #library");
    let find = document.querySelector("#nav-movies #find");

    let containerFind = document.getElementById("container-find-movies");
    let containerRecommended = document.getElementById("container-recommended-movies");
    let containerLibrary = document.getElementById("container-library-movies");

    function navigability(id){

        recmendation.classList.remove('active');
        find.classList.remove('active');
        library.classList.remove('active');

        containerFind.classList.add('visually-hidden');
        containerRecommended.classList.add('visually-hidden');
        containerLibrary.classList.add('visually-hidden');

        if(id === "recommended"){
            recmendation.classList.add('active');
            containerRecommended.classList.remove('visually-hidden');
        }else if(id === "library"){
            library.classList.add('active');
            containerLibrary.classList.remove('visually-hidden');
        }else if(id === "find"){
            find.classList.add('active');
            containerFind.classList.remove('visually-hidden');
        }
    }
    
    function cargarRecommendedMovies(){
        const spinner = document.getElementById('spinner');
        spinner.style.display = 'block';

        fetch('<?= BASE_URL.'api/1.0/user/recommendedMovies' ?>', {
            method: 'GET',   
        })

        .then(res => res.json())
        .then(async result => {

            
            const moviePromises = result.data.map(async result => itemMovie(result.movie, await getCommentsMovie(result.movie.ids.trakt)));
            const movies = await Promise.all(moviePromises);
            
            const fragmento = document.createDocumentFragment();
            movies.forEach(movie => {
                movie.querySelector('#btn-addMovie').addEventListener('click', () => addMylibraryEventClick(movie.getAttribute("key")));
                fragmento.appendChild(movie);
            });
            containerRecommended.appendChild(fragmento);
            spinner.style.display = 'none';

        })
        .catch(err => console.log(err));
   
    }

    function setEventToBtnFind(){

        let btnFind = document.querySelector("#container-find-movies #btn-find");
        let inputFind = document.querySelector("#container-find-movies #input-find");
        let containerResultFind = document.querySelector('#container-result-find');

        
        let btnFindByCategory = document.querySelectorAll(".container-categories .category");
        console.log(btnFindByCategory)
        btnFind.addEventListener("click", async ()=>{
                
                let result = await findMovie(inputFind.value);

                const moviePromises = result.map(async result => itemMovie(result.movie, await getCommentsMovie(result.movie.ids.trakt)));
                const movies = await Promise.all(moviePromises);

                const fragmento = document.createDocumentFragment();
                movies.forEach(movie => {
                    movie.querySelector('#btn-addMovie').addEventListener('click', () => addMylibraryEventClick(movie.getAttribute("key")));
                    fragmento.appendChild(movie);
                });

                const children = Array.from(containerResultFind.children);
                for (let i = 0; i < children.length; i++) {
                    children[i].remove();
                }
                containerResultFind.appendChild(fragmento);
                spinner.style.display = 'none';

        });

   
        let btnCategories = document.querySelector("#search-bar #btn-categories");
        console.log(btnCategories.innerText);

        btnFindByCategory.forEach((btn,i)=>{
            
            btn.addEventListener('click', async ()=>{

                btnCategories.innerText = btn.innerText;

                let result = await findMovieByGenre(btn.innerText);
                
                //console.log(result.map(console.log("hola")));

                const moviePromises = result.map(async movie =>itemMovie(movie, await getCommentsMovie(movie.ids.trakt)));
                const movies = await Promise.all(moviePromises);

                console.log("-->"+movies);
                const fragmento = document.createDocumentFragment();
                movies.forEach(movie => {
                    console.log('-->'+movie);
                    movie.querySelector('#btn-addMovie').addEventListener('click', () => addMylibraryEventClick(movie.getAttribute("key")));
                    fragmento.appendChild(movie);
                });

                const children = Array.from(containerResultFind.children);
                for (let i = 0; i < children.length; i++) {
                    children[i].remove();
                }
                containerResultFind.appendChild(fragmento);
                spinner.style.display = 'none';

            });

        });


    }
   
    function findMovie(name){

        const spinner = document.getElementById('spinner');
        spinner.style.display = 'block';

        return new Promise((resolve, reject) => {

            fetch('<?= BASE_URL.'api/1.0/user/findMovie?movie=' ?>'+name, {
                method: 'GET',
            })
            .then(res => res.json())
            .then(result => resolve(result.data))
            .catch(err => reject(err));

        });
    } 

    function findMovieByGenre(genre){

        const spinner = document.getElementById('spinner');
        spinner.style.display = 'block';

        return new Promise((resolve, reject) => {

            fetch('<?= BASE_URL.'api/1.0/user/findMovieByCategory?genre=' ?>'+genre, {
                method: 'GET',
            })
            .then(res => res.json())
            .then(result => resolve(result.data))
            .catch(err => reject(err));

        });

    }

    function addMylibraryEventClick(idMovie){

        fetch('<?= BASE_URL.'api/1.0/user/addMovie'?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({'idMovie' : idMovie}) 
        })

        .then(res => res.json())
        .then(data => {

            if(data.stateResult){

                let itemMovie = document.querySelector('#item-movie[key="'+idMovie+'"]');

                let itemMovieCopy = itemMovie.cloneNode(true);

                setMovieInMyLibrary(itemMovieCopy);

                btnAddMovieOrigin =  itemMovie.querySelector('#btn-addMovie');
                btnAddMovieOrigin.classList.remove("link-success");
                btnAddMovieOrigin.classList.add("link-secondary");

            }

            console.log(data.inf);

        })
        .catch(err => {alert(err);});

    }

    function setMovieInMyLibrary(itemMovieCopy){

        let btnAddMovie = itemMovieCopy.querySelector('#btn-addMovie')

        //btnAddMovie.removeEventListener('click',  () => addMylibraryEventClick(movie.ids.trakt));//TODO: no esta removiendo el evento
        btnAddMovie.textContent = "Remove 💀"
        btnAddMovie.style.color = 'red';
   
        btnAddMovie.addEventListener('click', () => removeMovieFromLibraryEventClick(itemMovieCopy));

        containerLibrary.appendChild(itemMovieCopy);
    }

    function cargarMyLibrary(){

        fetch('<?= BASE_URL.'api/1.0/user/myListMovies'?>', {
            method: 'GET',
        })

        .then(res => res.json())
        .then( async result => {

            if(result.stateResult){

                    const moviePromises = result.data.map(async movie => itemMovie(movie , await getCommentsMovie(movie.ids.trakt)));
                    const movies = await Promise.all(moviePromises);

                    const fragmento = document.createDocumentFragment();

                    movies.forEach(movie => {

                        setMovieInMyLibrary(movie);
        
                    }); 
            } 
        })
        .catch(err => console.log("No se a podio añadir la pelicula a la biblioteca. Error: "+err));
    }

    function removeMovieFromLibraryEventClick(itemMovie){
        
        fetch('<?= BASE_URL.'api/1.0/user/deltedMovie?idMovie='?>'+itemMovie.getAttribute("key"), {
            method: 'DELETE',
        })

        .then(res => res.json())
        .then(result => {

            if(result.stateResult){
                itemMovie.remove();
                console.log("Eliminado con exito");
            } 
        })
        .catch(err => console.log("No se a podio añadir la pelicula a la biblioteca. Error: "+err));
        //TODO : Eliminnar de BD
    }

    

</script>

