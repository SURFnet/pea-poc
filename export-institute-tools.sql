SELECT
    I.full_name AS `institute`,
    T.name AS `tool`,
    GROUP_CONCAT(DISTINCT C.name ORDER BY C.id SEPARATOR ', ') AS `categories`,
    AT.name AS `alternative_tool`,
    CASE
        WHEN IT.published_at IS NULL THEN 'unpublished'
        WHEN IT.status IS NULL THEN 'unrated'
        ELSE IT.status
    END AS `status`,
    IT.why_unfit,
    IT.description_1, IT.description_2,
    IT.extra_information_title, IT.extra_information,
    IT.support_email_1, IT.support_title_1,
    IT.support_email_2, IT.support_title_2,
    IT.manual_url_1, IT.manual_title_1,
    IT.manual_url_2, IT.manual_title_2,
    IT.video_url_1, IT.video_title_1,
    IT.video_url_2, IT.video_title_2,
    IT.created_at,
    IT.updated_at,
    IT.published_at
  FROM institute_tool IT
  LEFT JOIN tool_categories TC ON IT.tool_id = TC.tool_id
  LEFT JOIN categories C ON IT.institute_id = C.institute_id AND TC.category_id = C.id
 INNER JOIN institutes I ON IT.institute_id = I.id
 INNER JOIN tools T ON IT.tool_id = T.id
  LEFT JOIN tools AT ON IT.alternative_tool_id = AT.id
 GROUP BY IT.id;
