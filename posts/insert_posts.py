"""
This program parses posts passed to it, and inserts them into the post table.
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
            cursor.execute("INSERT INTO post (written, slug, name, description, content, filename) VALUES (%s, %s, %s, %s, %s, %s) ON DUPLICATE KEY UPDATE written = VALUES(written), slug = VALUES(slug), name = VALUES(name), description = VALUES(description), content = VALUES(content), filename = VALUES(filename), updated=NOW()",
                    (meta['written'], meta['slug'], meta['name'], meta['description'], str(content), post))
        conn.commit()
    finally:
        conn.close()
