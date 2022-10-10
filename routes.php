$app->group('/api', function(){
    $this->get('/zip-codes/{codeZip}', 'ApiController:codeZip');
});
