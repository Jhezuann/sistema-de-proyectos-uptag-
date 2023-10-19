<div class="d-flex justify-content-center">
  <div class="mt-3">
    <ul class="pagination">
      <li class="page-item <?php echo $_GET['pagina']<=1 ? 'disabled' : '' ?>">
        <a class="page-link" href="index.php?pagina=<?php echo $_GET['pagina']-1?>">Anterior</a>
      </li>
      
      <?php for ($i=0; $i<$totalProyectos; $i++): ?>
      <li class="page-item <?php echo $_GET['pagina']==$i+1 ? 'active' : '' ?>">
        <a class="page-link" href="index.php?pagina=<?php echo $i+1 ?>"><?php echo $i+1 ?></a>
      </li>
      <?php endfor ?>  
      
      <li class="page-item <?php echo $_GET['pagina']>=$totalProyectos ? 'disabled' : '' ?>">
        <a class="page-link" href="index.php?pagina=<?php echo $_GET['pagina']+1?>">Siguiente</a>
      </li>
    </ul>
  </div>
</div>