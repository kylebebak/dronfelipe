<meta written="2015-12-03" slug="clustering_location_history" name="How to cluster location history records" description="Clustering location history records (latitude, longitude, timestamp). Algorithm and applications." />

<header>
  <h2>
    How and Why to Cluster Location History Records
  </h2>
</header>



<h3>Motivation</h3>
<p>
  If you have an Android phone with location services turned on, whenever your phone is connected to the internet it sends <b>location history records</b> to Google's servers, one per minute. This is true of Microsoft's and Apple's phones as well. These records contain three important fields: <code>latitude</code>, <code>longitude</code>, and <code>timestamp</code>. If your phone is usually with you, these records give a very complete picture of where you were at any time in the past.
</p>

<p>
  Their usefulness, however, is limited by their number: location history is <b><em>too detailed</em></b>. In a year your phone will send 500,000 records to be saved in a database. Executing queries on millions of records to uncover patterns is difficult for both users and hardware. What's worse is that the records are semantically indentical. We know intuitively that 600 consecutive records with the same geographic coordinates represent something very different from a sequence of 30 records starting at home and ending at work, so we need a classification that makes this clear.
</p>

<div class="paragraph">
  This is where clustering comes in. It accomplishes two important goals:
  <ul>
    <li>compresses the data</li>
    <li>groups records into distinct, meaningful entities</li>
  </ul>
  I'll describe these entities to explain the basic idea, then I'll look at the algorithm to show that the clustering is simple, fast, and predictable. At the end I'll talk about some applications.
</div>

<h3>Visits, Trips and Locations</h3>
<p>
  We return to the intuition that a sequence of records in the same location are different from a sequence that starts at A and ends at B. Our first logical entity, then, will serve to group consecutive stationary records into visits. Specifically, a <b><code>visit</code></b> is a sequence of <code>N</code> or more records separated by no more than a distance <code>R</code>. A visit knows its <code>lat</code>, <code>lon</code>, <code>start_time</code>, and <code>end_time</code>.
</p>

<p>
  A sequence of records which is not stationary and cannot be grouped into a visit is necessarily grouped into a <b><code>trip</code></b> between two visits. Every pair of consecutive visits is connected by a trip. A trip knows its <code>displacement</code>, <code>distance</code>, <code>start_time</code>, <code>end_time</code>, <code>start_visit</code>, and <code>end_visit</code>. By giving the trip pointers to the first and last location history records from which it was formed, these records can be used to reconstruct the trip's route if necessary.
</p>

<p>
  The last entity we define allows us to cluster visits <em>(and hence trips, which are defined in terms of their start and end visits)</em> into groups. We define a <b><code>location</code></b> as the average latitude and longitude of a group of visits separated by no more than a distance <code>R</code>, the same R that we used for clustering records into visits. The motivation is simple: locations allow us to see hundreds of otherwise distinct visits as belonging to the same equivalence class, e.g. visits to <b>home</b>, or visits to <b>work</b>. They also group trips into equivalence classes, e.g. trips <b>from home to work</b>, or <b>from work to home</b>.
</p>

<div class="paragraph">
  Assigning locations to visits and trips allows us to answer aggregate questions, like:
  <ul>
    <li>Where do I spend most of my time (top 5 places) on Saturdays?</li>
    <li>How many times have I been to my girlfriend's house, on the weekend?</li>
    <li>Over the past 6 months, at what time on average do I leave work on Friday?</li>
    <li>How long on average does it take me to get to work?</li>
  </ul>
</div>

<p>
  When run on 18 months (~750,000 records) of my location history, this process turned up 4,400 visits (and hence 4,400 trips) belonging to 750 locations. I used <code>N=6, R=10m</code> as clustering parameters, meaning that 6 minutes or more spent in the same 10 meter radius was considered a visit. 750,000 records were thus transformed into 10,000 vastly more expressive ones.
</p>

<h3>Clustering Algorithm</h3>
<p>
  Without constraints, clustering spatial data is a hard problem with imprecise solutions. Even using heuristics it's hard to beat quadratic time complexity, the intuition being that to assign each record to a cluster, you need to compare it with all the other records in the set to know which ones are nearby.
</p>

<p>
  However, when clustering location history records we exploit the fact that the <b>distance</b> metric by which we define <b>nearness</b> includes <b><em>time</em></b>. We only group records that occur within a small span of time, and because we can sort the records by timestamp (indeed, they're already sorted for us), independent of geographic coordinates, we can get the clustering done in <b><em>linear</em></b> time.
</p>

<p>
  We also exploit our classification scheme: we are looking for an alternating sequence of visits and trips. We know what the visits look like, and <em>we don't even need to find the trips.</em> Every trip is bookended by a pair of visits, so once we have the visits we simply read through the unclustered records that connect them and instantiate our trips.
</p>

<p>
  Here's how it works. In the first pass through the data, each record is compared to the most recently instantiated <b>potential visit</b>, whose latitude and longitude are the average coordinates of the records it contains. If the record is within a distance <code>R</code> of the visit, it gets added to the visit, and the visit's latitude and longitude are recalculated to reflect the addition of the record. If the record is not within <code>R</code> of the visit, a new potential visit is instantiated containing only this record. In Python:
  <pre style="margin: 0; line-height: 125%">visit <span style="color: #333333">=</span> <span style="color: #007020">None</span>
<span style="color: #008800; font-weight: bold">for</span> each record <span style="color: #000000; font-weight: bold">in</span> records:
<span style="color: #888888"># records sorted from oldest to newest</span>
    <span style="color: #008800; font-weight: bold">if</span> visit <span style="color: #000000; font-weight: bold">and</span> visit<span style="color: #333333">.</span>distance_to(record) <span style="color: #333333">&lt;=</span> R:
        visit<span style="color: #333333">.</span>add(record)
    <span style="color: #008800; font-weight: bold">else</span>:
        visit <span style="color: #333333">=</span> Visit(record)
</pre>

</p>

<p>
  Next, potential visits with fewer than <code>N</code> constituent records are discarded. The remaining visits have pointers to their first and last records, which allows the second pass through the data to focus only on the unclustered records. From each sequence of records linking one visit to another, a trip is instantiated.
</p>

<p>
  A third pass, this time through the visits, generates locations. We compare each visit to all existing locations, and add it to the first location within distance <code>R</code> of the visit. We recompute this location's latitude and longitude as the average coordinates of the visits it contains, <b><em>weighted by the duration of these visits</em></b>. If no nearby location exists, we instantiate a new location containing only this visit.
</p>

<h3>Discussion</h3>
<p>
  Depending on implementation, the clustering is deterministic: in any case, the only way to significantly change the results is to tweak the parameters <code>N</code> and <code>R</code>. Fortunately, the effects of these parameters are predictable. Increasing <code>N</code> causes shorter visits to disappear, and increasing <code>R</code> makes some visits slightly longer, and causes some adjacent locations to be merged.
</p>

<p>
  Exploiting structure and constraints inherent in the data, we avoid the guesswork used in algorithms like <b>k-means</b>, resulting in a simple, predictable algorithm that produces clusters whose meaning is clear. As for time complexity, any conceivable clustering will have to read each record at least once, so our linear implementation, at least for clustering visits, is <em>as fast as possible</em>.
</p>

<p>
  An aside: it seems plausible that a sequence of unfortunately placed records, moving slowly but surely in one direction, could <b>"stretch out"</b> a visit so that it contains pairs of records that are separated by a distance much greater than <code>R</code>. <a href="assets/visit_logarithmic_growth">I've shown here</a> that for the most pathological sequence of records, the <b>"diameter"</b> of the visit grows as <code>R * ln(n)</code>, where <code>n</code> is the number of records in the visit. With real data, stretched visits occur with vanishing probability. Still, it's reassuring that even in the worst case they grow slowly.
</p>

<p>
  Another aside: because locations have no temporal component, we can't exploit time in our distance metric to decrease the number of comparisons we make to instantiate them. However, these comparisons are made between visits and locations, not between pairs of records. Comparing 4,400 visits to 750 locations is <b><em>much less expensive</em></b> than doing pairwise comparison between 750,000 records. If these comparisons become costly, we can always consider other techniques for finding neighbors, like <a href="http://www.bigfastblog.com/geohash-intro">geohashing</a>.
</p>


<h3>Applications</h3>
<p>
  Visits and trips give a good account of your movements, and even some of your habits, but this story can be enriched by knowing <b><em>what</em></b> you're doing and <b><em>with whom</em></b>, instead of just where you're going. Google, for example, is in a good position to generate these stories. With the <a href="https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding">Geocoding API</a>, addresses can be looked up for geographical coordinates, so that automatic descriptions and photos are generated for your locations.
</p>

<p>
  If you take pictures and use Google Photos, these could be linked to the visits or trips on which they were taken. If you go to a store, or a restaurant, or a museum, you are probably visiting a location for which Google has records and metadata, and your location instance could be a given a pointer to theirs. Suppose you use Google Wallet, or have your bank account configured to send you transaction alerts via text message. Financial transactions could be attached to the visits on which they occur.
</p>

<p>
   Most importantly, visits, trips and locations point to the users that generate them, and this is where social networking comes into play. Imagine you <b>"friend"</b> another user. Your visits that <b>"overlap"</b> spatially and temporally with theirs could automatically be <b>"shared"</b> between you, so that you could look at photos, comments, or whatever else your friends add to these visits. You could look at a location and see the list of friends who shared a visit there with you, then select one of these friends and see the list of visits you shared with them. And so on.
</p>

<h3>Privacy</h3>
<p>
  For allowing users to track themselves in the privacy of their own phones, it's easy to write a client-side application to cluster and store location history records. But part of the tradition of the web is users trading privacy for convenience, or simply for the opposite of privacy. I think the networking features of location history, which can't be implemented client-side, will be embraced by users in the future. I wrote this clustering program, and built a front end for <a href="../location_history">exploring the results</a>, in 2014. Google probably thought of it before that, but didn't release <a href="https://www.google.com/maps/timeline">timeline</a> until 2015, because it's an idea that gives us pause. We need to get comfortable tracking ourselves before we are tracked by our friends.
</p>

