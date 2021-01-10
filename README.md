# comment-client-drom

## Установка
`composer require gadon78921/comment-client-drom`

## Использование
```
$httpClient = HttpClient::create();
$mapper = new CommentMapper();
$commentApiHttp = new CommentApiHttp($httpClient, $mapper, 'http://example.com');
$response = $commentApiHttp->list($limit, $offset);
```
### Получить список комментариев:
```
$response = $commentApiHttp->list($limit, $offset);
```
### Добавить комментарий:
```
$comment = new Comment();
$comment->setName('Ivan');
$comment->setText('Comment_text');
$response = $commentApiHttp->add($comment);
```
### Обновить комментарий:
```
$comment = new Comment();
$comment->setId(5);
$comment->setName('Ivan');
$comment->setText('Comment_text');
$response = $commentApiHttp->update($comment);
```
