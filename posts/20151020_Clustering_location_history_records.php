<article>

<header>
  <h2>
  How and Why to Cluster Your Phone's Location History Records
  </h2>
</header>


<section>

  <p>
    If you have an Android phone with location services turned on, whenever your phone is connected to the internet it is sending location history records to Google's servers, one per minute. This is true of Microsoft's and Apple's phones as well. These records contain three important fields: <b>latitude</b>, <b>longitude</b>, and <b>timestamp</b>. If your phone is always with you, these records give a very complete picture of where you were at any time in the past.
  </p>

  <p>
    Their usefulness, however, is limited by their number: location history is <b><em>too detailed</em></b>. In a year your phone will send 500,000 records to be saved in a database. Executing queries on millions of records to uncover patterns is difficult for both users and hardware. What's worse is that the records are semantically indentical. We know intuitively that 600 consecutive records with the same lat-lon coordinates represent something very different from a sequence of 30 records starting at home and ending at work, so we need a classification system that makes this clear.
  </p>

  <p>
    This is where clustering comes in. It serves the dual purpose of <b>grouping records into distinct, meaningful entities</b> and <b>greatly compressing the data</b>. First I'll explain the basic idea, and then I'll go into the math to show that the clustering scheme is simple, fast, and predictable.
  </p>

  <p>

  </p>

  <p>
    When run on about 18 months of my location data, this process turned up 4400 visits (clusters of points where I'd been stationary somewhere for more than 6 minutes) that correspond to 750 unique locations. Trips are the sequences of moving points that occur between visits. The data was a lot of fun to explore. Probed with simple queries it can answer interesting aggregate questions, like where are the top five places I spend time on Saturdays, or, over a period of 6 months, at what time on average did I leave work on each of the different weekdays.
  </p>

</section>


</article>
