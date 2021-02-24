<div class="footer">
    <div class="row justify-content-center links-rodape">
        <a href="{{ route('index') }}">Inicio</a>  |  
        <a href="#">Eventos Acadêmicos</a>  |  
        <a hfer="#" data-toggle="modal" data-target="#modalInfo" >Sobre</a> 
    </div>
    <div class="row">
      <div class="col-sm-12 separador">
        <hr>
      </div>
    </div>
    <div class="row" style="padding-left: 150px; padding-right: 150px;">
      <div class="col-sm-4 coluna">
        <div class="row justify-content-center">
          <small>Desenvolvidor por:</small>
        </div>
      </div>
      <div class="col-sm-4 coluna">
        <div class="row justify-content-center">
          <small>Apoio:</small>
        </div>
      </div>
      <div class="col-sm-4 coluna">
        <div class="row justify-content-center">
          <small>Redes Sociais:</small>
        </div>
      </div>
    </div>
    <div class="row justify-content-center" style="padding-left: 150px; padding-right: 150px;">
      <div class="col-sm-4 coluna">
        <div class="row justify-content-center">
          <a href="http://www.lmts.uag.ufrpe.br/br" name="lmts">
            <img src="{{asset('img/lmts.png')}}" style="margin:20px 0 20px 0" width="200px;" >
          </a>
        </div>
        
        <div class="row justify-content-center" style="text-align:center; font-size: 14px;">
          Laboratório Multidisciplinar de<br>
          Tecnologias Sociais
        </div>
        {{-- <div class="row justify-content-center" style="margin-top:20px; text-align:center">
          <small>
            Avenida Bom Pastor, s/n.º<br>
            Bairro Boa Vista - CEP:<br>
            55292-270 - Garanhuns - PE
          </small>
        </div> --}}
        
      </div>
      <div class="col-sm-4 coluna">
        {{-- <div class="row justify-content-center">
          <h3>LMTS</h3>
        </div>
        <div class="row sobre justify-content-center">
          <a href="http://www.lmts.uag.ufrpe.br/br/content/apresenta%C3%A7%C3%A3o">Quem Somos</a>
        </div>
        <div class="row sobre justify-content-center">
          <a href="http://www.lmts.uag.ufrpe.br/br/content/equipe">Equipe</a>
        </div>
        <div class="row sobre justify-content-center">
          <a href="http://www.lmts.uag.ufrpe.br/br/noticias">Notícias</a>
        </div>
        <div class="row sobre justify-content-center">
          <a href="http://www.lmts.uag.ufrpe.br/br/content/projetos">Projetos</a>
        </div> --}}
        
        <div class="row justify-content-center">
          <div clss="col-sm-4">
            <a href="http://ww3.uag.ufrpe.br/">
              <img style="width:77px" src="{{asset('img/logoUfape.svg')}}" alt=""  width="100px;" height="125px;">
            </a>
          </div>
          <div class="col-sm-8" style="position: relative; top: 50px; font-size: 14px;">
            <div class="row justify-content-center">
              Universidade Federal Rural<br>
              do Agreste de Pernambuco
            </div>
          </div>
        </div>
        
      </div>
      {{-- <div class="col-sm-3 coluna">
        <div class="row justify-content-center">
          <h3>CONTATO</h3>
        </div>
        <div class="row justify-content-center">
          <a href="mailto:lmts@ufrpe.br">lmts@ufrpe.br</a>
        </div>
      </div> --}}
      <div class="col-sm-4 coluna social-network">
        
        <div class="row justify-content-center">
          <div class="social">
            <a href="https://www.facebook.com/LMTSUFAPE/">
              <img src="{{asset('img/icons/facebook-square-brands.svg')}}" alt="">
            </a>
          </div>
          <div class="social">
            <a href="https://www.instagram.com/lmts_ufape/">
              <img src="{{asset('img/icons/instagram-brands.svg')}}" alt="">
            </a>
          </div>
          <div class="social">
            <a href="https://twitter.com/lmtsufape">
              <img src="{{asset('img/icons/twitter-brands.svg')}}" alt="">
            </a>
          </div>
          {{-- <div class="social">
            <a href="https://br.linkedin.com/in/lmts-ufrpe-0b25b9196?trk=people-guest_people_search-card">
              <img src="{{asset('img/icons/linkedin-brands.svg')}}" alt="">
            </a>
          </div> --}}
        </div>
      </div>
    </div>
</div>