"""
This program parses posts passed to it, and inserts them into the post table.
The slug field is a unique identifier for each post record. It is passed
in the final segment of the URL to tell the posts controller which post
it needs to look up. The filename is stored as metadata. The filename, and
hence the extension, are decoupled from the URL and posts controller.
"""
import sys

from bs4 import BeautifulSoup

import pymysql.cursors

for post in sys.argv[1:]:
    content = BeautifulSoup(open(post), "html.parser")
    meta = content.find("meta")

    conn = pymysql.connect(
        host='localhost',
        user='root',
        password='root',
        db='dronfelipe',
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

    try:
        with conn.cursor() as cursor:
            cursor.execute("INSERT INTO post (written, slug, name, description, content, filename) VALUES (%s, %s, %s, %s, %s, %s) ON DUPLICATE KEY UPDATE written = VALUES(written), slug = VALUES(slug), name = VALUES(name), description = VALUES(description), content = VALUES(content), updated=NOW()",
                    (meta['written'], meta['slug'], meta['name'], meta['description'], str(content), post))
        conn.commit()
    finally:
        conn.close()
